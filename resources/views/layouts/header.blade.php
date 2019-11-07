<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Навигация</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <a class="nav-item nav-link active" href="{{ url('/') }}">Главная <span class="sr-only">(current)</span></a>
            @if(!Auth::check())
            <a class="nav-item nav-link" href="{{ route('register') }}">Зарегистрироваться</a>
            <a class="nav-item nav-link" href="{{ route('login') }}">Войти</a>
            @endif
            @if(Auth::check())
             <a class="nav-item nav-link" onclick="getMessageForm()" href="#">Написать</a>
             <div class="nav_left">
                @if(Auth::user()->hasRole(['admin', 'moderator']))
                     <a class="nav-item nav-link" href="/dashboard">В админку</a>
                @endif
                 <a class="nav-item nav-link disabled" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                     <i class="fas fa-sign-out-alt"></i> Выйти
                 </a>
                    <a class="nav-link" href="#" role="button">
                        <img class="img-avatar" src="{{Auth::user()->avatar_url}}">
                    </a>
             </div>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endif


        </div>
    </div>
</nav>

@push('scripts')
    <script>
        function getMessageForm() {
            axios.get("{{route('create')}}")
                .then(resp => {
                    // console.log(resp.data);
                    $(resp.data).insertBefore($('.guestbook'));
                    store_message();
                });
        }

        function store_message() {
            $('form.reply_form').on('submit', function(e) {
                e.preventDefault();

                axios({
                    method: 'post',
                    url: "{{route('store')}}",
                    data: $(this).serialize(),
                    config: { headers: {'Content-Type': 'multipart/form-data' }}
                })
                    .then(resp => {
                        $(resp.data).insertBefore($('.guestbook'));
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

