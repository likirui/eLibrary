<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookLoan;
use App\Models\Book;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookLoanController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }
    public function requestbook(Request $request, $id){

        $book = Book::find($id);
        $book->loan = 'Yes';
        $book->save();

        //Book Loan
        $bookloan = BookLoan::create([
            'user_id' => $request->user_id,
            'book_id' => $request->id,
            'status'=>'Pending Approval',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Book details updated successfully',
            'book' => $book,
        ]);
    }

    public function bookloans()
    {
        $books = BookLoan::whereNull('deleted_at')
           ->where('status', 'Pending Approval')
           ->with('user', 'book')
           ->get();
          return response()->json([
        'status' => 'success',
        'books' => $books,
      ]);
    }

    /*public function approvebook(Request $request, $id){
        $request->validate([
            'due_date' => 'required'
        ]);

        // Set loan_date to the current date and time
        $bookloan = BookLoan::find($id);

        $formattedDueDate = Carbon::createFromFormat('mdY', $request->due_date)->format('Y-m-d');

        $bookloan->loan_date = Carbon::now();
        $bookloan->due_date = $request->$formattedDueDate;
        $bookloan->status = 'Approved';
        $bookloan->added_by = $request->added_by;
        $bookloan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Book Loan approved successfully',
            'book' => $book,
        ]);
    }*/

    public function approvebook(Request $request, $id){
        $request->validate([
            'due_date' => 'required'
        ]);

        // Set loan_date to the current date and time
        $bookloan = BookLoan::find($id);

        $bookloan->loan_date = Carbon::now();
        $bookloan->due_date = $request->due_date;
        $bookloan->status = 'Approved';
        $bookloan->added_by = $request->added_by;
        $bookloan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Book Loan approved successfully',
            'bookloan' => $bookloan,
        ]);
    }

    public function returnbook(Request $request, $id){

       $book_details = Book::find($id);

        $book = Book::find($book_details->id);
        $book->loan = 'No';
        $book->save();

       // Set book loan to returned
       $bookloan = BookLoan::find($id);

       $bookloan->return_date = Carbon::now();
       $bookloan->status = 'Returned';
       $bookloan->added_by = $request->added_by;
       $bookloan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Book returned successfully',
            'book' => $book,
        ]);
    }

    public function approvedbooks()
    {
        $books = BookLoan::whereNull('deleted_at')
           ->where('status', 'Approved')
           ->with('user', 'book')
           ->get();
          return response()->json([
        'status' => 'success',
        'books' => $books,
      ]);
    }

    public function myloans(Request $request, $id)
    {
        $books = BookLoan::whereNull('deleted_at')
           ->where('status', 'Approved')
           ->where('user_id', $id)
           ->with('user', 'book')
           ->get();
          return response()->json([
        'status' => 'success',
        'books' => $books,
      ]);
    }


    public function extendbook(Request $request, $id){
        $request->validate([
            'due_date' => 'required'
        ]);

        // Set loan_date to the current date and time
        $bookloan = BookLoan::find($id);
        $bookloan->due_date = $request->due_date;
        $bookloan->extended = 'Yes';
        $bookloan->extension_date = $request->due_date;
        $bookloan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Book Loan extended successfully',
            'bookloan' => $bookloan,
        ]);
    }

    public function gettotals() {
        $totalBooks = Book::count();
        $borrowedBooks = Book::where('loan', 'Yes')->count();
        $pendingApproval = BookLoan::where('status', 'Pending Approval')->count();
        $totalUsers = User::count();

        return response()->json([
            'totalBooks' => $totalBooks,
            'borrowedBooks' => $borrowedBooks,
            'pendingApproval' => $pendingApproval,
            'totalUsers' => $totalUsers,
        ]);
    }

    public function totalborrowedbooks(Request $request) {
        $result = DB::table('book_loans')
            ->select(DB::raw('MONTHNAME(loan_date) as month, COUNT(*) as count'))
            ->groupBy('month')
            ->get();

        $months = $result->pluck('month');
        $counts = $result->pluck('count');

        return response()->json([
            'months' => $months,
            'counts' => $counts,
        ]);
    }

    public function totalregisteredusers() {
        $usersCount = User::select(
            DB::raw('COUNT(*) as count'),
            DB::raw('MONTHNAME(created_at) as month')
        )
        ->groupBy('month')
        ->get();

        $months = $usersCount->pluck('month');
        $counts = $usersCount->pluck('count');

        return response()->json([
            'months' => $months,
            'counts' => $counts,
        ]);
    }

    public function userborrowedbooks(Request $request, $id) {
        $result = DB::table('book_loans')
        ->where('user_id', $id)
            ->select(DB::raw('MONTHNAME(loan_date) as month, COUNT(*) as count'))
            ->groupBy('month')
            ->get();

        $months = $result->pluck('month');
        $counts = $result->pluck('count');

        return response()->json([
            'months' => $months,
            'counts' => $counts,
        ]);
    }

    public function usertotalpenalty(Request $request, $id) {

        $penaltiesSum = BookLoan::select(
            DB::raw('SUM(IFNULL(penalty_amount, 0)) as sum'),
            DB::raw('MONTHNAME(created_at) as month')
        )
        ->where('user_id', $id)
        ->groupBy('month')
        ->get();

        $months = $penaltiesSum->pluck('month');
        $sums = $penaltiesSum->pluck('sum');

        return response()->json([
            'months' => $months,
            'sums' => $sums,
        ]);
    }

    public function usertotals(Request $request, $id) {
        $totalBooks = Book::count();
        $borrowedBooks = BookLoan::where('user_id', $id)->where('status', 'Approved')->count();
        $pendingApproval = BookLoan::where('user_id', $id)->where('status', 'Pending Approval')->count();
        $totalUsers = User::where('id', $id)->count();

        return response()->json([
            'totalBooks' => $totalBooks,
            'borrowedBooks' => $borrowedBooks,
            'pendingApproval' => $pendingApproval,
            'totalUsers' => $totalUsers,
        ]);
    }


}
