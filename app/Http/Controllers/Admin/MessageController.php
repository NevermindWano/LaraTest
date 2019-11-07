<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Messages;
use Illuminate\Http\Request;
class MessageController extends Controller
{
    public function get()
    {
        $messages = Messages::select([
            'messages.*','users.name as user'
        ])
                    ->where('status_id', MESSAGE_MODERATE)
                    ->join('users', 'users.id', '=', 'messages.user_id')
                    ->paginate();

        return json_encode($messages);
    }

    public function setStatus(Request $request, Messages $message)
    {
        $message->status_id = $request->get('status');
        $message->save();

        return 1;
    }
}
