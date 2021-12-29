<?php

namespace App\Http\Controllers;

use App\Models\Reader;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

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
    public function index()
    {
        $readers = Reader::paginate(10);

        return response()->json([
            'success_readers' => true,
            'message' => 'Successful reader listing',
            'data' => $readers
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
            return response()->json(['error_readers' => $validator->messages()], 200);
        }

        try {
            $reader = Reader::firstOrCreate($data);
        } catch (\Exception $e) {
            return response()->json([
                'success_readers' => false,
                'message' => 'Could not create reader.',
            ], 500);
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
        //
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
}
