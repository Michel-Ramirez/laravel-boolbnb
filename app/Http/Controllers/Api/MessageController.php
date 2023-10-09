<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Take data request
        $data = $request->all();

        // Validate
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email',
            'message' => 'required|string',
            'house_id' => 'required|exists:houses,id'
        ], [
            'name.required' => 'Il nome è obbligatorio',
            'name.string' => 'Il nome non è valido',
            'name.max' => 'Il nome è troppo lungo',
            "email.required" => "La mail è obbligatoria",
            "email.email" => "La mail inserita non è valida",
            "message.required" => "Il messaggio è obbligatorio",
            "message.string" => "Il messaggio non è valido",
        ]);

        // If errors return errors
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 400);
        }

        // New Message
        $new_message = new Message();
        // Fill Message
        $new_message->fill($data);
        // Save Message
        $new_message->save();

        // Return 204
        return response(null, 204);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
