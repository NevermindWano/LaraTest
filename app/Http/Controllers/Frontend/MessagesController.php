<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Messages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Kris\LaravelFormBuilder\FormBuilder;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $messages = Messages::where('parent_id', 0)
            ->where('status_id', MESSAGE_SUCCESS)
            ->orderByDesc('updated_at')
            ->paginate();


        $answers = [];
        foreach ($messages as $message)
        {
            $answer = Messages::where('parent_id', $message->id)
                ->get();

            if (!$answer->isEmpty())
                $answers[$message->id] = $answer;
        }

        return view('frontend.messages',compact('messages', 'answers'));
    }



    public function create(FormBuilder $formBuilder)
    {
        if (!Auth::id()) {
            $slot = 'Чтобы оставить запись необходима авторизация';
            return view('components.alerts.fail_alert', compact('slot'))->render();
        }

        return $this->replyForm($formBuilder);
    }


    public function reply(FormBuilder $formBuilder, Messages $message)
    {
        if (!Auth::id()) {
            $slot = 'Чтобы оставить запись необходима авторизация';
            return view('components.alerts.fail_alert', compact('slot'))->render();
        }

        return $this->replyForm($formBuilder, $message);
    }

    private function replyForm(FormBuilder $formBuilder, Messages $message = null)
    {
        $reply = new Messages();
        $id = ($message) ? $message->id : 0;
        $form = $formBuilder->create('App\Helpers\ReplyForm', [
            'method' => 'POST',
            'class'  => 'reply_form',
            'url'    => route('reply_store', $id),
            'model'  => $reply
        ]);

        return view('components.answer_form', compact('form'))->render();
    }

    public function storeReply(StoreMessageRequest $request, Messages $message)
    {
        return $this->store($request, $message->id);
    }



    public function store(StoreMessageRequest $request, int $parent_id = 0)
    {
        $data = $request->all();
        $message = new Messages();
        $message->fill($data);
        $message->user_id = Auth::id();
        $message->parent_id = $parent_id;
        $message->status_id = 1;
        $message->save();

        $slot = 'Ваше сообщение успешно сохранено и отправлено на модерацию';
        return view('components.alerts.success_alert', compact('slot'))->render();
    }


    public function edit(FormBuilder $formBuilder, Messages $message)
    {
        $form = $formBuilder->create('App\Helpers\ReplyForm', [
            'method' => 'POST',
            'class'  => 'edit_form',
            'url'    => route('update', $message->id),
            'model'  => $message
        ]);

        return view('components.answer_form', compact('form'))->render();
    }

    public function update(StoreMessageRequest $request, Messages $message)
    {
        $success = DB::transaction(function () use($message, $request) {
            $checkMessage = Messages::where('parent_id', $message->id)->get();
            if (!$checkMessage->isEmpty())
                return false;
            $message->update($request->all());
            return true;
        });
        if (!$success) {
            $slot = 'Уже кто-то ответил на Ваше сообшение. Отредактировать нельзя';
            return view('components.alerts.fail_alert', compact('slot'))->render();
        }
        $slot = 'Ваше сообщение обновлено';
        return view('components.alerts.success_alert', compact('slot'))->render();
    }


}
