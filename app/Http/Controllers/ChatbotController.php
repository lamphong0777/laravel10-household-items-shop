<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ChatbotController extends Controller
{
    // Trang chat
    public function index()
    {
        return view('chatbot.chatbot');
    }

// Gửi tin nhắn tới Rasa
    public function sendMessage(Request $request)
    {
        $client = new Client();
        $response = $client->post('http://localhost:5005/webhooks/rest/webhook', [
            'json' => [
                'sender' => 'user', // Có thể là ID người dùng
                'message' => $request->message,
            ]
        ]);
        return response()->json(json_decode($response->getBody(), true));
    }
}
