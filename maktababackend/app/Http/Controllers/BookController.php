<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class BookController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $books = Book::whereNull('deleted_at')->get();
        return response()->json([
            'status' => 'success',
            'books' => $books,
        ]);
    }

    public function avbooks()
    {
        $books = Book::whereNull('deleted_at')
    ->where('loan', 'No')
    ->get();

return response()->json([
    'status' => 'success',
    'books' => $books,
]);
    }

    public function store(Request $request){

        $request->validate([
            'name' => 'required',
            'publisher' => 'required',
            'isbn' => 'required',
            'category' =>'required',
            'subcategory' => 'required',
            'description' => 'required',
            'pages' => 'required',
            'image' => 'required|image',
            'added_by'=>'required'
        ]);

        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        $book = Book::create([
            'name' => $request->name,
            'publisher' => $request->publisher,
            'isbn'=>$request->isbn,
            'category'=>$request->category,
            'sub_category' => $request->subcategory,
            'image' => 'images/'.$imageName,
            'pages' => $request->pages,
            'description' => $request->description,
            'added_by' => $request->added_by
        ]);

        return response()->json([
            'message' => 'Book created successfully',
            'book' => $book,
        ]);
      }

      public function show($id)
      {
          $book = Book::find($id);
          return response()->json([
              'status' => 'success',
              'book' => $book,
          ]);
      }

      /*public function update(Request $request, $id)
      {
        $request->validate([
            'name' => 'required',
            'publisher' => 'required',
            'isbn' => 'required',
            'category' =>'required',
            'sub_category' => 'required',
            'description' => 'required',
            'pages' => 'required',
            'image' => 'required|image',
            'added_by'=>'required'
        ]);

        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);

          $book = Book::find($id);
          $book->name = $request->name;
          $book->publisher = $request->publisher;
          $book->isbn = $request->isbn;
          $book->category = $request->category;
          $book->sub_category  = $request->sub_category;
          $book->image = 'images/'.$imageName;
          $book->pages = $request->pages;
          $book->description = $request->description;
          $book->added_by = $request->added_by;
          $book->save();

          return response()->json([
              'status' => 'success',
              'message' => 'Book details updated successfully',
              'book' => $book,
          ]);
      }*/

      public function update(Request $request, $id){
              $request->validate([
        'name' => 'required',
        'publisher' => 'required',
        'isbn' => 'required',
        'category' => 'required',
        'sub_category' => 'required',
        'description' => 'required',
        'pages' => 'required',
        'added_by' => 'required',
        'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $book = Book::find($id);

    if ($request->hasFile('image')) {
        // If an image is provided, update it
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);
        $book->image = 'images/' . $imageName;
    }

    // Update other fields
    $book->name = $request->name;
    $book->publisher = $request->publisher;
    $book->isbn = $request->isbn;
    $book->category = $request->category;
    $book->sub_category = $request->sub_category;
    $book->pages = $request->pages;
    $book->description = $request->description;
    $book->added_by = $request->added_by;

    $book->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Book details updated successfully',
        'book' => $book,
    ]);
}

}
