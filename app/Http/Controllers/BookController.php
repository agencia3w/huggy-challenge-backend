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
    public function index(Request $request)
    {
        $type = $request->get('type');
        $books = Book::orderBy('title')->paginate(10);
        $resume = "";

        foreach($books as $item){
            $resume .= $item->id . " - " . $item->title ."\n";
        }

        $data = ($type === 'resume') ? $resume : $books;

        return response()->json([
            'success_books' => true,
            'message' => 'Successful book listing',
            'data' => $data
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
        $publisher = $request->only('publisher_name', 'publisher_code', 'publisher_phone');
        $data = $request->only('title', 'genre', 'author', 'year', 'pages', 'language', 'edition', 'isbn');
        $merge = array_merge($publisher, $data);

        $validator = Validator::make($merge, [
            'title' => 'required',
            'genre' => 'required',
            'author' => 'required',
            'year' => 'required|date_format:Y',
            'pages' => 'required|numeric',
            'language' => 'required',
            'edition' => 'required',
            'publisher_name' => 'required',
            'publisher_code' => 'required',
            'publisher_phone' => 'required',
            'isbn' => 'required',
        ]);

        $data['publisher'] = json_encode($publisher);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error_books' => $validator->messages(),'message' => 'error'], 200);
        }

        try {
            $book = Book::firstOrCreate($data);
        } catch (\Exception $e) {
            return response()->json([
                'success_books' => false,
                'message' => 'Não foi possível cadastrar o livro.',
            ], 500);
        }

        return response()->json([
            'success_books' => true,
            'message' => 'Livro cadastrado com sucesso!',
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
                'success_books' => false,
                'message' => 'book not found',
            ], 404);
        }

        $publisher = $request->only('publisher_name', 'publisher_code', 'publisher_phone');
        $data = $request->only('title', 'genre', 'author', 'year', 'pages', 'language', 'edition', 'isbn');
        $merge = array_merge($publisher, $data);

        $validator = Validator::make($merge, [
            'title' => 'required',
            'genre' => 'required',
            'author' => 'required',
            'year' => 'required|date_format:Y',
            'pages' => 'required|numeric',
            'language' => 'required',
            'edition' => 'required',
            'publisher_name' => 'required',
            'publisher_code' => 'required',
            'publisher_phone' => 'required',
            'isbn' => 'required',
        ]);

        $data['publisher'] = json_encode($publisher);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error_books' => $validator->messages()], 200);
        }

        try {
            $book->update($data);
        } catch (\Exception $e) {
            return response()->json([
                'success_books' => false,
                'message' => 'Could not update book.',
            ], 500);
        }

        return response()->json([
            'success_books' => true,
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
                'success_books' => false,
                'message' => 'book not found',
            ], 404);
        }

        try {
            $book->delete();
        } catch (\Exception $e) {
            return response()->json([
                'success_books' => false,
                'message' => 'Could not remove book.',
            ], 500);
        }

        return response()->json([
            'success_books' => true,
            'message' => 'Book removed successfully.',
        ], Response::HTTP_OK);
    }
}
