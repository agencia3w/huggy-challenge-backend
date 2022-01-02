<?php

namespace App\Http\Controllers;

use App\Mail\EmailNotification;
use App\Models\Book;
use App\Models\Reader;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\ReaderRequest;

class ReaderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->get('type');
        $readers = Reader::orderBy('name')->paginate(10);
        $resume = $readers->map(function($reader){
            return "$reader->id - $reader->name";
        })->implode(PHP_EOL);

        $data = ($type === 'resume') ? $resume : $readers;

        return response()->json([
            'success' => true,
            'message' => 'Leitores listados com sucesso',
            'data' => $data
        ], Response::HTTP_OK);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ReaderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReaderRequest $request)
    {
        $data = $request->only('name', 'email', 'phone', 'address', 'district', 'state', 'city', 'zipCode', 'birthday');

        try {
            $reader = Reader::firstOrCreate($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível cadastrar o leitor',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        //CRM integration
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => '85110ace1272867bb83868417a5d88e2'
            ])->post('https://api.pipe.run/v1/persons', [
                'name' => $request->name,
                'contact_emails' => [$request->email],
                'contact_phones' => [$request->phone],
                'address' => $request->address,
                'district' => $request->district,
                'address_postal_code' => $request->zipCode,
                'birth_day' => $request->birthday
            ]);
        } catch (\Exception $e) {
            $data = [
                'message' => 'Não foi possível cadastrar o leitor no CRM',
                'subject' => 'Erro integração CRM',
                'type' => 'error'
            ];
            $this->sendMail($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Leitor cadastrado com sucesso',
            'data' => $reader
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reader  $reader
     * @return \Illuminate\Http\Response
     */
    public function show(Reader $reader)
    {
        return response()->json([
            'success' => true,
            'message' => 'Leitor listado com sucesso',
            'data' => $reader
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ReaderRequest  $request
     * @param  \App\Models\Reader  $reader
     * @return \Illuminate\Http\Response
     */
    public function update(ReaderRequest $request, Reader $reader)
    {
        $data = $request->only('name', 'email', 'phone', 'address', 'district', 'state', 'city', 'zipCode', 'birthday');

        try {
            $reader->update($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível atualizar o leitor',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'success' => true,
            'message' => 'Leitor atualizado com sucesso',
            'data' => $reader
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reader  $reader
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reader $reader)
    {
        try {
            $reader->delete();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível excluir o leitor',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->noContent();
    }

    /**
     * Store readed book
     *
     * @param  \App\Models\Reader  $reader
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function readedBook(Reader $reader, Book $book)
    {
        try {
            $reader->books()->attach($book);
            Cache::forget('readedTotal');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível informar o livro lido para o leitor',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'success' => true,
            'message' => 'Livro lido informado com sucesso',
        ], Response::HTTP_OK);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function readedTotal()
    {
        $data = Cache::remember('readedTotal', 60 * 60, function () {
            return Reader::withCount('books')
                ->orderBy('name')
                ->get()
                ->map(function (Reader $item) {
                    return "$item->name - $item->books_count";
                })
                ->implode(PHP_EOL);
        });

        return response()->json([
            'success' => true,
            'message' => 'Listagem de livros lidos por leitor com sucesso',
            'data' => $data
        ], Response::HTTP_OK);
    }

    /**
     * Send Email Notification
     *
     * @return \Illuminate\Http\Response
     */
    public function sendMail($data)
    {
        \Mail::to('paulinho@agencia3w.com.br')->send(new EmailNotification($data));
    }
}
