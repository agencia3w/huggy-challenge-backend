<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Requests\BookRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $books = Cache::remember('books', 60 * 60, function () {
            return Book::orderBy('title')->paginate(10);
        });

        $resume = Cache::remember('books_resume', 60 * 60, function () use ($books) {
            return $books->map(function ($book) {
                return "$book->id - $book->title";
            })->implode(PHP_EOL);
        });

        $type = $request->get('type');
        $data = ($type === 'resume') ? $resume : $books;

        return response()->json([
            'success' => true,
            'message' => 'Livros listados com sucesso',
            'data' => $data
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\BookRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookRequest $request)
    {
        $publisher = $request->only('publisher_name', 'publisher_code', 'publisher_phone');
        $data = $request->only('title', 'genre', 'author', 'year', 'pages', 'language', 'edition', 'isbn');

        $data['publisher'] = json_encode($publisher);

        try {
            $book = Book::firstOrCreate($data);
            $this->clearCache();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível cadastrar o livro',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'success' => true,
            'message' => 'Livro cadastrado com sucesso',
            'data' => $book
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        return response()->json([
            'success' => true,
            'message' => 'Livro listado com sucesso',
            'data' => $book
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\BookRequest  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(BookRequest $request, Book $book)
    {
        $publisher = $request->only('publisher_name', 'publisher_code', 'publisher_phone');
        $data = $request->only('title', 'genre', 'author', 'year', 'pages', 'language', 'edition', 'isbn');

        $data['publisher'] = json_encode($publisher);

        try {
            $book->update($data);
            $this->clearCache();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível atualizar o livro',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'success' => true,
            'message' => 'Livro atualizado com sucesso',
            'data' => $book
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        try {
            $book->delete();
            $this->clearCache();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível excluir o livro',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->noContent();
    }

    /**
     * Clear Book Cache
     *
     */
    private function clearCache()
    {
        Cache::forget('books');
        Cache::forget('books_resume');
    }
}
