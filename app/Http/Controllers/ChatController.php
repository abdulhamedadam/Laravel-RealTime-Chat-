<?php

namespace App\Http\Controllers;

use App\Events\NewChatMessage;
use App\Models\ChatMessages;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ChatController extends Controller
{
    public function rooms(Request $request)//return aall rooms
    {
        return ChatRoom::all();
    }

    public function messages(Request $request,$roomId)
    {
        return ChatMessages::where('chat_room_id',$roomId)->with('user')->orderBy('created_at','DESC')->get();
    }

    public function newMessage(Request $request,$roomId)
    {
       $newMessage=new ChatMessages;
       $newMessage->user_id=Auth::id();
       $newMessage->chat_room_id=$roomId;
       $newMessage->message=$request->message;
       $newMessage->save();

       broadcast(new NewChatMessage($newMessage))->toOthers();
       return $newMessage;
    }
}
