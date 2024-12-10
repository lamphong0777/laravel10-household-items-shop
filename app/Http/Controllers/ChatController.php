<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $messages = Message::where('user_id', $userId)->orderBy('created_at', 'asc')->get();
        return view('chat.index', compact('messages'));
    }

    public function adminIndex($id)
    {
        $messages = Message::where('user_id', $id)->orderBy('created_at', 'asc')->get();
        $userName = User::find($id)->name;
        return view('chat.admin-index', compact('messages', 'id', 'userName'));
    }

    public function sendMessage(Request $request)
    {

        $user = Auth::user();

        $message = Message::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_admin' => auth()->user()->role == 2 ?? false, // Xác định nếu là admin
        ]);

        broadcast(new MessageSent($message, auth()->id()))->toOthers();

        return response()->json(['message' => $message]);
    }

    public function sendMessageAdmin(Request $request, $id)
    {

        $user = Auth::user();

        $message = Message::create([
            'user_id' => $id,
            'staff_id' => $user->id,
            'message' => $request->message,
            'is_admin' => auth()->user()->role == 2 ?? false, // Xác định nếu là admin
        ]);

        broadcast(new MessageSent($message, $id));

        return response()->json(['message' => $message]);
    }


    public function customerChatIndex(Request $request)
    {
//        $userHaveChat = Message::select('user_id')
//            ->orderByDesc('created_at', 'desc')
//            ->get();
        $userHaveChat = DB::table('messages')
            ->join('users', 'messages.user_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'users.email')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy(DB::raw('MAX(messages.created_at)'), 'desc')
            ->get();
//        dd($userHaveChat);
        $dayNow = Carbon::now();
        $fiveDaysAgo = $dayNow->copy()->subDays(5);
        return view('admin.customer_chat.index', compact('userHaveChat', 'fiveDaysAgo', 'dayNow'));
    }
}
