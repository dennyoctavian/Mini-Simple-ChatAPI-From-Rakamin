<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function sendNewMessage(Request $request, $id) {
        $user = Auth::user();
        $userReceive = User::find($id);
        $request = $request->all();

        $ChatRoom = ChatRoom::create([
            'user_one' => $user->id,
            'user_two' => $userReceive->id
        ]);

        $chat = Chat::create([
            'chat_room_id'=> $ChatRoom->id,
            'user_id' => $user->id,
            'text' => $request['text']
        ]);

        $listChat = $ChatRoom->listChat->sortBy('created_at');

        return response()->json([
            'status' => 'success',
            'message' => 'Chat Success Send',
            'data' => $listChat
        ]);
    }

    public function sendMessage(Request $request, $id) {
        $request = $request->all();
        $user = Auth::user();
        $chatRoom = ChatRoom::find($id);
        $chat = Chat::create([
            'chat_room_id'=> $chatRoom->id,
            'user_id' => $user->id,
            'text' => $request['text']
        ]);
        $listChat = $chatRoom->listChat->sortBy('created_at');
        return response()->json([
            'status' => 'success',
            'message' => 'Chat Success Send',
            'data' => $listChat
        ]);
        
    }

    // User able to list messages from specific user
    public function listMessage($id) {
        $user = Auth::user();
        $userReceive = User::find($id);
        $ListchatRoom = ChatRoom::all();
        foreach ($ListchatRoom as $chatRoom) {
           if(($chatRoom->user_one == $user->id && $chatRoom->user_two == $id) || ($chatRoom->user_one == $id && $chatRoom->user_two == $user->id)) {
                $chatRoomNow = $chatRoom;
           } else {
                $chatRoomNow = null;
           }
        }

        if ($chatRoomNow == null) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Chat dengan orang yang dituju tidak ditemukan'
            ]);
        }
        $listChat = $chatRoomNow->listChat->sortBy('created_at');

        return response()->json([
            'status' => 'success',
            'data' => $listChat
        ]);
    }

    public function chatRoom() {
        $user = Auth::user();
        $listChatRoom = [];
        $ListchatRoomAll = ChatRoom::all();
        foreach ($ListchatRoomAll as $chatRoom) {
            if($chatRoom->user_one == $user->id || $chatRoom->user_two == $user->id) {
                array_push($listChatRoom, $chatRoom);
            }
         }
        return response()->json([
            'status' => 'success',
            'data' => $listChatRoom
        ]);
    }
}
