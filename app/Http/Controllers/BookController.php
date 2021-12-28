<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Requests\BookRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Successful book listing',
            'data' => $books
        ], Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only('title', 'genre', 'author', 'year', 'pages', 'language', 'edition', 'publisher', 'isbn');
        $validator = Validator::make($data, [
            'title' => 'required',
            'genre' => 'required',
            'author' => 'required',
            'year' => 'required|date_format:Y',
            'pages' => 'required|numeric',
            'language' => 'required',
            'edition' => 'required',
            'publisher' => 'required',
            'isbn' => 'required',
        ]);

        $data['publisher'] = json_encode($request->publisher);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        try {
            $book = Book::firstOrCreate($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not create book.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Book created successfully.',
            'data' => $book
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (empty($book)) {
            return response()->json([
                'success' => false,
                'message' => 'book not found',
            ], 404);
        }

        $data = $request->only('title', 'genre', 'author', 'year', 'pages', 'language', 'edition', 'publisher', 'isbn');
        $validator = Validator::make($data, [
            'title' => 'required',
            'genre' => 'required',
            'author' => 'required',
            'year' => 'required|date_format:Y',
            'pages' => 'required|numeric',
            'language' => 'required',
            'edition' => 'required',
            'publisher' => 'required',
            'isbn' => 'required',
        ]);

        $data['publisher'] = json_encode($request->publisher);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        try {
            $book->update($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not update book.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Book update successfully.',
            'data' => $book
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if (empty($book)) {
            return response()->json([
                'success' => false,
                'message' => 'book not found',
            ], 404);
        }

        try {
            $book->delete();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not remove book.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Book removed successfully.',
        ], Response::HTTP_OK);
    }
}
