<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use App\Models\UserMessage;

class DashboardController extends BaseController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    public function index() {
        $users = User::where('id', '!=', \Auth::user()->id)->get();
        return view('dashboard', compact('users'));
    }

    public function load_previous_messages(Request $request) {

        $sender = [];
        $sender['id'] = \Auth::user()->id;

        $receiver = [];
        $receiver['id'] = $request->receiver;

        UserMessage::where('sender_id', $receiver['id'])
                ->where('receiver_id', $sender['id'])
                ->update(['seen_status' => 1]);

         
        $previousConversation = UserMessage::with('sender', 'receiver')->where(function ($q) use ($sender, $receiver) {
                            $q->where(function ($q) use ($sender, $receiver) {
                                $q->where('sender_id', $sender['id']);
                            })->where(function ($q) use ($sender, $receiver) {
                                $q->where('receiver_id', $receiver['id']);
                            });
                        })
                        ->orWhere(function ($q) use ($sender, $receiver) {
                            $q->where(function ($q) use ($sender, $receiver) {
                                $q->where('sender_id', $receiver['id']);
                            })->where(function ($q) use ($sender, $receiver) {
                                $q->where('receiver_id', $sender['id']);
                            });
                        })
                        ->orderby('id', 'asc')->take(50)->get()->reverse();
        return response()->json(['success' => true, 'data' => ['messages' => $previousConversation]]);
    }

    public function sendmessage(Request $request) {
        $receiver = [];

        $receiver['receiver_id'] = $request->receiver;
        $receiver['sender_id'] = \Auth::user()->id;
        $message = UserMessage::create([
                    'receiver_id' => $request->receiver,
                    'sender_id' => \Auth::user()->id,
                    'message' => $request->message,
                    'created_at' => \Carbon\Carbon::now()
        ]);
        return response()->json([
                    'data' => $message,
                    'success' => true,
                    'message' => 'Message sent successfully'
        ]);
    }

}
