<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Storage;

use Avatar;
use Kris\LaravelFormBuilder\FormBuilder;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(FormBuilder $formBuilder)
    {
        $user = new User();
        $form = $formBuilder->create('App\Helpers\RegisterForm', [
            'method' => 'POST',
            'url'    => '/register',
            'model'  => $user,
            'id'     => 'reg'
        ]);
        return view('auth.register_new', compact('form'));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request)));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'birthday' => 'required|date',
            'girl'  => 'required',
            'avatar'    => 'image|max:100'

        ],
            [
                'name.required' => 'Значение :attribute обязательно',
                'email.required' => 'Значение :attribute обязательно',
                'email.email' => 'Введите орректный email',
                'password.required' => 'Значение :attribute обязательно',
                'password.min' => 'Пароль должен быть не меньше :min символов',
                'password.confirmed' => 'Пароли должны совпадать',
                'birthday.required' => 'Значение :attribute обязательно',
                'girl.required'  => 'Укажите свой пол',
                'avatar.image'    => 'Аватар должен быть картинкой',
                'avatar.max'    => 'Размер файла не должен превышать :max',
            ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(Request $request)
    {
        $data = $request->all();

//       dd($request->file('avatar'));


//        dd($extension);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'birthday' => $data['birthday'],
            'girl' => $data['girl'],
        ]);

//

        $user->assignRole('user');

        $avatar = $request->file('avatar');

        if (!empty($avatar))
        {
            $extension =  $avatar->getClientOriginalExtension();
            $fileName = 'avatar' . '.' . $extension;
            $avatar = file_get_contents($avatar->getPathname());
        }
        else
        {
            $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
            $fileName = 'avatar.png';

        }
//
//        $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
//        $fileName = 'avatar.png';

        Storage::put('avatars/'.$user->id.'/' . $fileName, (string) $avatar);

        $user->avatar = $fileName;
        $user->save();


        return $user;
    }
}
