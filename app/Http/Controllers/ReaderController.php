<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Reader;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class ReaderController extends Controller
{

    // protected $user;

    // public function __construct()
    // {
    //     $this->user = JWTAuth::parseToken()->authenticate();
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->get('type');
        $readers = Reader::orderBy('name')->paginate(10);
        $resume = "";

        foreach ($readers as $item) {
            $resume .= $item->id . " - " . $item->name . "\n";
        }

        $data = ($type === 'resume') ? $resume : $readers;

        return response()->json([
            'success_readers' => true,
            'message' => 'Successfull reader listing',
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
        //Validate data
        $data = $request->only('name', 'email', 'phone', 'address', 'district', 'state', 'city', 'zipCode', 'birthday');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'birthday' => 'required|date_format:Y-m-d'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error_readers' => $validator->messages(), 'message' => 'error'], 200);
        }

        //Insert reader into db

        try {
            $reader = Reader::firstOrCreate($data);
        } catch (\Exception $e) {
            return response()->json([
                'success_readers' => false,
                'message' => 'Could not create reader.',
            ], 500);
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
            // send email notification
        }

        return response()->json([
            'success_readers' => true,
            'message' => 'Reader created successfully.',
            'data' => $reader
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reader  $reader
     * @return \Illuminate\Http\Response
     */
    public function show(Reader $reader)
    {
        if (empty($reader)) {
            return response()->json([
                'success_readers' => false,
                'message' => 'Reader not found',
            ], 404);
        }

        return response()->json([
            'success_readers' => true,
            'message' => 'Successfull reader listing',
            'data' => $reader
        ], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reader  $reader
     * @return \Illuminate\Http\Response
     */
    public function edit(Reader $reader)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reader  $reader
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $reader = Reader::find($id);

        if (empty($reader)) {
            return response()->json([
                'success_readers' => false,
                'message' => 'Reader not found',
            ], 404);
        }

        $data = $request->only('name', 'email', 'phone', 'address', 'district', 'state', 'city', 'zipCode', 'birthday');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'birthday' => 'required|date_format:Y-m-d'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error_readers' => $validator->messages()], 200);
        }

        try {
            $reader->update($data);
        } catch (\Exception $e) {
            return response()->json([
                'success_readers' => false,
                'message' => 'Could not update reader.',
            ], 500);
        }

        return response()->json([
            'success_readers' => true,
            'message' => 'Reader update successfully.',
            'data' => $reader
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reader  $reader
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reader = Reader::find($id);

        if (empty($reader)) {
            return response()->json([
                'success_readers' => false,
                'message' => 'Reader not found',
            ], 404);
        }

        try {
            $reader->delete();
        } catch (\Exception $e) {
            return response()->json([
                'success_readers' => false,
                'message' => 'Could not remove reader.',
            ], 500);
        }

        return response()->json([
            'success_readers' => true,
            'message' => 'Reader removed successfully.',
        ], Response::HTTP_OK);
    }

    /**
     * Store CRM PipeRun
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendCRM(Request $request)
    {
        dd($request->all());

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => '85110ace1272867bb83868417a5d88e2'
            ])->post('https://api.pipe.run/v1/persons', [
                'name' => 'Paulo Neto 5',
                'contact_emails' => ['paulinho@agencia3w.com.br'],
                'contact_phones' => ['75 991115905'],
                'address' => 'Rua Ilha Bela, 150',
                'district' => 'Papagaio',
                'address_postal_code' => '44059230',
                'birth_day' => '1983-10-05'
            ]);

            dd($response['data']);
        } catch (\Exception $e) {
            return response()->json([
                'success_readed' => false,
                'message' => 'Could not add book.' . $e,
            ], 500);
        }

        return response()->json([
            'success_readed' => true,
            'message' => 'Readed Book successfully.',
        ], Response::HTTP_OK);
    }

    /**
     * Store readed book
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function readedBook(Request $request)
    {
        $reader = Reader::find($request->reader_id);
        $book = Book::find($request->book_id);

        if (empty($reader) || empty($book)) {
            return response()->json([
                'error' => true,
                'success_readed' => false,
                'message' => 'error',
            ], 404);
        }

        try {
            $reader->books()->attach($request->book_id);
        } catch (\Exception $e) {
            return response()->json([
                'success_readed' => false,
                'message' => 'Could not add book.' . $e,
            ], 500);
        }

        return response()->json([
            'success_readed' => true,
            'message' => 'Readed Book successfully.',
        ], Response::HTTP_OK);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function readedTotal()
    {
        $readers = Reader::withCount('books')->orderBy('name')->get();

        // dd($readers);
        $data = "";

        foreach ($readers as $item) {
            $data .= $item->name . " - " . $item->books_count . "\n";
        }

        return response()->json([
            'success_readers' => true,
            'message' => 'Successful reader listing',
            'data' => $data
        ], Response::HTTP_OK);
    }
}
