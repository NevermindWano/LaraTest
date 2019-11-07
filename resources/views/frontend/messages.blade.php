@extends('layouts.app')

@section('content')
    <div class="guestbook">
        @foreach($messages as $message)
            <div class="media mt-5" id="{{$message->id}}">
                <img class="align-self-start mr-3" src="storage/avatars/{{$message->user->id}}/avatar.png"
                     alt="Generic placeholder image">
                <div class="media-body ml-2 mb-6">
                    <h5 class="mt-1">{{$message->user->name}}</h5>
                    <p>{{$message->message}}</p>
                    <span class="link reply" onclick="getReplyForm({{$message->id}})">Ответить</span>
                    @if ($message->user_id == Auth::id() && !array_key_exists($message->id, $answers))
                        <span onclick="getEditForm({{$message->id}})" class="link edit">Редактировать</span>
                    @endif
                    @if(array_key_exists($message->id, $answers))
                        @foreach($answers[$message->id] as $answer)
                            @if($answer->status_id == MESSAGE_SUCCESS)
                                <div class="media mt-3 mb-6" id="{{$answer->id}}">
                                    <a class="pr-3" href="#">
                                        <img src="storage/avatars/{{$answer->user->id}}/avatar.png" alt="Generic placeholder image">
                                    </a>
                                    <div class="media-body ml-2">
                                        <h5 class="mt-0">{{$answer->user->name}}</h5>
                                        <p>{{$answer->message}}</p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{$messages->render()}}

    @push('scripts')
        <script>
            function getReplyForm(id) {
                let axios = window.axios;

                axios.get('/reply/' + id)
                    .then(resp => {
                            $(resp.data).insertAfter($('#' + id + " span.reply"));
                            store_reply(id);
                    });
            }

            function getEditForm(id) {
                let axios = window.axios;

                axios.get('/edit/' + id)
                    .then(resp => {
                        // $(resp.data).insertAfter($('#' + id + " a.reply"));
                        let element = $('.guestbook #' + id + ' p');
                        $(element).replaceWith(resp.data);
                        update(id);
                    });
            }

            function update(id) {
                $('form.edit_form').on('submit', function(e) {
                    e.preventDefault();
                    axios({
                        method: 'post',
                        url: '/update/' + id,
                        data: $(this).serialize(),
                        config: { headers: {'Content-Type': 'multipart/form-data' }}
                    })
                        .then(resp => {
                            $('.guestbook #' + id + ' .media-body').prepend(resp.data);
                            let text = $('form.edit_form textarea').val();
                            $(this).replaceWith('<p>' + text + '</p>');
                        })
                        .catch(error => {
                            let err_message = error.response.data.errors.message[0];
                            alert(err_message);
                            // $(err_message).insertAfter($('#' + id + " a.reply"));
                        })

                })
            }


            function store_reply(id) {
                $('form.reply_form').on('submit', function(e) {
                    e.preventDefault();

                    axios({
                        method: 'post',
                        url: '/replystore/' + id,
                        data: $(this).serialize(),
                        config: { headers: {'Content-Type': 'multipart/form-data' }}
                    })
                        .then(resp => {
                            $('.guestbook #' + id + ' .media-body').prepend(resp.data);
                            $(this).remove();
                        })
                        .catch(error => {
                            let err_message = error.response.data.errors.message[0];
                            alert(err_message);
                            // $(err_message).insertAfter($('#' + id + " a.reply"));
                        })

                })
            }




        </script>
    @endpush

@endsection


