<?php

namespace App\Http\Controllers;

use App\Events\GroupMessageSent;
use App\Models\Group;
use App\Models\Message;
use Illuminate\Http\Request;

class GroupMessageController extends Controller
{
    public function sendMessage(Request $request, $groupId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $group = Group::findOrFail($groupId);

        if (!$group->users->contains(auth()->id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message = new Message();
        $message->text = $request->message;
        $message->group_id = $groupId;
        // $message->save();

        GroupMessageSent::dispatch($message);



        return response()->json(['success' => 'Message sent successfully']);
    }
}
