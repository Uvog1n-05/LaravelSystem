<?php

namespace App\Http\Controllers;

use App\Models\Books;
use App\Models\BookBorrowing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Controller handling all book borrowing operations.
 * Manages borrowing books, returning them, and extending borrowing periods.
 */
class BookBorrowingController extends Controller
{
    /**
     * System-wide borrowing limits and settings
     */
    private const MAX_BOOKS_PER_USER = 5;    // Maximum number of books a user can borrow at once
    public const MAX_EXTENSIONS = 2;         // Maximum number of times a borrowing can be extended
    private const BORROW_PERIOD_DAYS = 14;   // Standard borrowing period in days
    private const EXTENSION_DAYS = 7;        // Number of days added when extending a borrowing

    public function borrow(Books $book): RedirectResponse
    {
        // Check if the book is available
        if (!$book->isAvailable()) {
            return back()->with('error', 'This book is currently not available for borrowing.');
        }

        // Check if user has reached maximum borrow limit
        $activeBorrows = BookBorrowing::where('user_id', auth()->id())
            ->whereNull('returned_date')
            ->count();

        if ($activeBorrows >= self::MAX_BOOKS_PER_USER) {
            return back()->with('error', 'You have reached the maximum number of books you can borrow (' . self::MAX_BOOKS_PER_USER . ').');
        }

        // Check if user already has an active borrow for this book
        $existingBorrow = BookBorrowing::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->whereNull('returned_date')
            ->first();

        if ($existingBorrow) {
            return back()->with('error', 'You have already borrowed this book.');
        }

        try {
            DB::transaction(function () use ($book) {
                // Decrease the number of available books
                $book->decrement('number_of_books');

                // Create new borrow record
                BookBorrowing::create([
                    'user_id' => auth()->id(),
                    'book_id' => $book->id,
                    'borrowed_date' => now(),
                    'due_date' => now()->addDays(self::BORROW_PERIOD_DAYS),
                    'extensions_count' => 0
                ]);
            });

            return back()->with('success', 'Book borrowed successfully. Please return it by ' . 
                now()->addDays(self::BORROW_PERIOD_DAYS)->format('M d, Y'));
        } catch (\Exception $e) {
            \Log::error('Error borrowing book: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while processing your request.');
        }
    }
    

    public function returnBook(Books $book): RedirectResponse
    {
        $borrow = BookBorrowing::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->whereNull('returned_date')
            ->first();

        if (!$borrow) {
            return back()->with('error', 'No active borrow found for this book.');
        }

        try {
            // Instead of directly marking the book returned, create a return request
            // that an admin must verify before the book is marked as returned.
            $borrow->update([
                'return_requested' => true,
                'return_requested_at' => now(),
            ]);

            return back()->with('success', 'Return requested. An admin will verify and complete the return shortly.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to return the book. Please try again.');
        }
    }

    /**
     * Allow a user to cancel a previously made return request.
     */
    public function cancelReturn(Books $book): RedirectResponse
    {
        $borrow = BookBorrowing::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->whereNull('returned_date')
            ->where('return_requested', true)
            ->first();

        if (!$borrow) {
            return back()->with('error', 'No pending return request found for this book.');
        }

        try {
            $borrow->update([
                'return_requested' => false,
                'return_requested_at' => null,
            ]);

            return back()->with('success', 'Return request cancelled.');
        } catch (\Exception $e) {
            \Log::error('Error cancelling return request: ' . $e->getMessage());
            return back()->with('error', 'Failed to cancel return request. Please try again.');
        }
    }

    /**
     * Admin: Approve and verify a user's return request.
     */
    public function approveReturn(BookBorrowing $borrowing): RedirectResponse
    {
        // Ensure current user is admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        if (!$borrowing->return_requested || $borrowing->returned_date) {
            return back()->with('error', 'No pending return request for this borrowing.');
        }

        try {
            DB::transaction(function () use ($borrowing) {
                // Mark as returned and record verifier
                $borrowing->update([
                    'returned_date' => now(),
                    'return_requested' => false,
                    'return_verified_by' => auth()->id(),
                    'return_verified_at' => now(),
                ]);

                // Increment book stock
                $borrowing->book->increment('number_of_books');
            });

            return back()->with('success', 'Return verified and completed.');
        } catch (\Exception $e) {
            \Log::error('Error approving return: ' . $e->getMessage());
            return back()->with('error', 'Failed to approve return. Please try again.');
        }
    }

    public function extend(Books $book): RedirectResponse
    {
        $borrow = BookBorrowing::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->whereNull('returned_date')
            ->first();

        if (!$borrow) {
            return back()->with('error', 'No active borrow found for this book.');
        }

        // Check if maximum extensions reached
        if ($borrow->extensions_count >= self::MAX_EXTENSIONS) {
            return back()->with('error', 'You have reached the maximum number of extensions (' . self::MAX_EXTENSIONS . ').');
        }

        // Only allow extension if not overdue
        if ($borrow->isOverdue()) {
            return back()->with('error', 'Cannot extend borrowing period for overdue books.');
        }

        try {
            DB::transaction(function () use ($borrow) {
                $borrow->update([
                    'due_date' => Carbon::parse($borrow->due_date)->addDays(self::EXTENSION_DAYS),
                    'extensions_count' => $borrow->extensions_count + 1
                ]);
            });

            return back()->with('success', 'Borrowing period extended by ' . self::EXTENSION_DAYS . ' days.');
        } catch (\Exception $e) {
            Log::error('Error extending borrow period: ' . $e->getMessage());
            return back()->with('error', 'Failed to extend borrowing period. Please try again.');
        }
    }

    /**
     * List user's borrowing history with pagination
     */
    public function history(): View
    {
        $borrowings = BookBorrowing::where('user_id', auth()->id())
            ->with(['book'])
            ->orderBy('borrowed_date', 'desc')
            ->paginate(10);

        return view('user.borrowing-history', [
            'borrowings' => $borrowings,
            'total' => $borrowings->total(),
            'currentPage' => $borrowings->currentPage()
        ]);
    }

    /**
     * List all borrowings for admin view
     */
    public function allBorrowings(\Illuminate\Http\Request $request): View
    {
        // Ensure user is admin
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $status = $request->query('status', 'all');

        $query = BookBorrowing::with(['book', 'user']);

        switch ($status) {
            case 'active':
                // Active = not returned and not currently requested for return
                $query->whereNull('returned_date')
                      ->where(function($q) {
                          $q->where('return_requested', false)->orWhereNull('return_requested');
                      });
                break;
            case 'returned':
                $query->whereNotNull('returned_date');
                break;
            case 'requested':
                $query->where('return_requested', true);
                break;
            case 'overdue':
                $query->whereNull('returned_date')->where('due_date', '<', now());
                break;
            case 'all':
            default:
                // no extra filter
                break;
        }

        $borrowings = $query->orderBy('borrowed_date', 'desc')->paginate(15)->appends($request->only('status'));

        return view('admin.borrowings', compact('borrowings', 'status'));
    }
}