<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = QueryBuilder::for(Book::class)
            ->defaultSort('-num_read_pages')
            ->get();
        return response()->json(BookResource::collection($books->take(5)));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'start_page' => 'required|integer',
            'end_page'  =>  'required|integer'
        ]);

        $book = Book::findOrFail($validatedData['book_id']);
        $user = User::findOrFail($validatedData['user_id']);

        $book->users()->attach([
            $validatedData['user_id'] => ['start_page' => $validatedData['start_page'],'end_page' => $validatedData['end_page']]
        ]);

        //update num of read pages
        $book->numOfPages();

        //Send Sms
        $user->sendSms($book);

        return new BookResource($book);
    }

}
