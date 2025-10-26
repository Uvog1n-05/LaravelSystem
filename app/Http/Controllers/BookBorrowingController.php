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

class BookBorrowingController extends Controller
{
    private const MAX_BOOKS_PER_USER = 5;
    private const MAX_EXTENSIONS = 2;
    private const BORROW_PERIOD_DAYS = 14;
    private const EXTENSION_DAYS = 7;

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
            // Create new borrow record
            BookBorrowing::create([
                'user_id' => auth()->id(),
                'book_id' => $book->id,
                'borrowed_date' => now(),
                'due_date' => now()->addDays(self::BORROW_PERIOD_DAYS),
            'extensions_count' => 0
            ]);

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
            DB::transaction(function () use ($borrow) {
                $borrow->update([
                    'returned_date' => now()
                ]);
            });

            return back()->with('success', 'Book returned successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to return the book. Please try again.');
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
}