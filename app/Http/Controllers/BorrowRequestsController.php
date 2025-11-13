<?php

namespace App\Http\Controllers;

use App\Models\BorrowRequest;
use App\Models\Books;
use App\Models\BookBorrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Controller for managing book borrowing requests.
 * Handles the request workflow from submission to approval/rejection.
 */
class BorrowRequestsController extends Controller
{
    /**
     * Display a list of all borrow requests (admin view).
     * Shows pending requests that need admin approval.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        // For admin view with optional sorting
    $sort = $request->query('sort', 'created_at');
    $direction = $request->query('direction', 'desc');
    $status = $request->query('status', null);

        $allowedSorts = ['created_at', 'user', 'book', 'status'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';

        $query = BorrowRequest::with(['user', 'book']);

        // Apply status filter if provided
        $allowedStatuses = ['pending', 'approved', 'declined'];
        if ($status && in_array($status, $allowedStatuses)) {
            $query = $query->where('borrow_requests.status', $status);
        }

        // Apply sorting
        if ($sort === 'user') {
            // join users to order by user name
            $query = $query->leftJoin('users', 'borrow_requests.user_id', '=', 'users.id')
                ->select('borrow_requests.*')
                ->orderBy('users.name', $direction);
        } elseif ($sort === 'book') {
            // join books to order by book title
            $query = $query->leftJoin('books', 'borrow_requests.book_id', '=', 'books.id')
                ->select('borrow_requests.*')
                ->orderBy('books.title', $direction);
        } else {
            $query = $query->orderBy($sort, $direction);
        }

        // Preserve status in pagination links
        $requests = $query->paginate(10)->appends($request->only(['sort', 'direction', 'status']));

        return view('admin.borrow-requests.index', compact('requests', 'sort', 'direction', 'status'));
    }

    private const MAX_TOTAL_BOOKS = 5;

    public function store(\Illuminate\Http\Request $request, Books $book): RedirectResponse
    {
        // Check if user already has a pending request for this book
        $existingRequest = BorrowRequest::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'You already have a pending request for this book.');
        }

        // Check if the book is available
        if (!$book->isAvailable()) {
            return back()->with('error', 'This book is currently not available for borrowing.');
        }

        // Count active borrowed books
        $activeBorrowings = \App\Models\BookBorrowing::where('user_id', auth()->id())
            ->whereNull('returned_date')
            ->count();

        // Count pending requests
        $pendingRequests = BorrowRequest::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->count();

        // Check if total of borrowed and requested books exceeds limit
        if (($activeBorrowings + $pendingRequests) >= self::MAX_TOTAL_BOOKS) {
            return back()->with('error', 
                'You can only have ' . self::MAX_TOTAL_BOOKS . ' books borrowed or requested at a time. ' .
                'You currently have ' . $activeBorrowings . ' borrowed books and ' . 
                $pendingRequests . ' pending requests.'
            );
        }

        // Validate and create the borrow request (store user's reason if provided)
        $data = $request->validate([
            'reason' => ['nullable', 'string', 'max:2000']
        ]);

        BorrowRequest::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'reason' => $data['reason'] ?? null,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Your borrow request has been submitted successfully.');
    }

    public function process(BorrowRequest $request, string $action): RedirectResponse
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        if (!$request->isPending()) {
            return back()->with('error', 'This request has already been processed.');
        }

        try {
            DB::beginTransaction();

            if ($action === 'approve') {
                // Check if book is still available
                if (!$request->book->isAvailable()) {
                    throw new \Exception('This book is no longer available for borrowing.');
                }

                // Count active borrowed books for the user
                $activeBorrowings = BookBorrowing::where('user_id', $request->user_id)
                    ->whereNull('returned_date')
                    ->count();

                // Skip limit check for admin users
                if (!$request->user->isAdmin() && $activeBorrowings >= self::MAX_TOTAL_BOOKS) {
                    throw new \Exception('User has reached the maximum number of borrowed books.');
                }

                // Update the request status first
                $request->update([
                    'status' => 'approved',
                    'processed_at' => now()
                ]);

                // Create borrowing record
                BookBorrowing::create([
                    'user_id' => $request->user_id,
                    'book_id' => $request->book_id,
                    'borrowed_date' => now(),
                    'due_date' => now()->addDays(14),
                    'extensions_count' => 0
                ]);

                // Decrease book count
                $request->book->decrement('number_of_books');

                $message = 'Borrow request approved successfully.';
            } else {
                // For declining requests
                $request->update([
                    'status' => 'declined',
                    'processed_at' => now()
                ]);
                
                $message = 'Borrow request declined successfully.';
            }

            DB::commit();
            return redirect()->route('admin.borrow-requests')->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error processing borrow request: ' . $e->getMessage(), [
                'request_id' => $request->id,
                'action' => $action,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', $e->getMessage());
        }
    }

    public function userRequests(): View
    {
        // For user to view their own requests
        $requests = BorrowRequest::with(['book'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.borrow-requests', compact('requests'));
    }
}
