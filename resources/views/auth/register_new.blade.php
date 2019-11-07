@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">
                        {!! form($form) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#avatar_input').on('change',function(){
            //get the file name
            var fileName = $(this).val().replace('C:\\fakepath\\', " ");
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        })

        $('form').on('submit', function(e) {
            e.preventDefault();

            let avatar = $('#avatar_input').prop('files')[0];

            var fd = new FormData(reg);
            fd.append('avatar', avatar);
            console.log(fd);

            axios({
                method: 'post',
                    url: '/register',
                    data: fd,
                    config: { headers:
                            {'Content-Type': 'multipart/form-data' }
                }
            }).then(resp => {
                 // window.location.pathname = '/';
            }).catch(error => {
                let err_messages = error.response.data.errors;
                for (let key in err_messages) {
                    alertError(key, err_messages[key]);
                }

            });

            // alert(err_message);
        })

        function alertError(keyInput, messages) {
            for (let key in messages) {
                // $(messages[key]).insertAfter();
                // $('input[name="'+ key +'"]').append(messages[key]);

                let element = $('input[name="'+ keyInput +'"]').parent(".form-group")[0];

                console.log(element);
                let alert = `<div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert">×</button>
                ${messages[key]}
                 </div>`;

                // $(alert).insertBefore(element);
                $(alert).insertAfter('.card-header');
            }
        }

        function getImg(v)
        {
            var $input = v;
            console.log($input);
            var fd = new FormData;

            fd.append('avatar', $input.prop('files')[0]);

            // $.ajax({
            //     url: '/tiz/'+ id +'/imgUpload',
            //     type: 'POST',
            //     data: fd,
            //     processData: false,
            //     contentType: false,
            //     success: function (data) {
            //         // table.ajax.reload();
            //         $(".imgupl-" + id).replaceWith(data);
            //     },
            //     error: function ()
            //     {
            //         alert('Ошибка');
            //     }
            // });
        }
    </script>

@endpush
