<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Login
    public function showLoginForm()
    {
        $pageConfigs = [
            'bodyClass' => "bg-full-screen-image",
            'blankPage' => true
        ];

        return view('/auth/login', [
            'pageConfigs' => $pageConfigs
        ]);
    }

    public function login(Request $request)
    {
        //Log::info(json_encode($request->all()));
        //Log::info('Login attempt: ' . $request->email);

        /*$request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'string'
        ]);*/

        // Validaciones personalizadas
        Validator::make(
            $request->input(),
            [
                'email' => 'required|string|email',
                'password' => 'required|string',
                'remember_me' => 'string'
            ],
            [
                'email.required' => 'Ingresa el correo electrónico',
                'email.string' => 'El correo electrónico no tiene un formato válido',
                'email.email' => 'El correo electrónico no tiene un formato válido',

                'password.required' => 'Ingresa la contraseña',
                'password.string' => 'La contraseña no tiene un formato válido'
            ]
        )->validate();

        $credentials = request(['email', 'password']);
        Log::info(json_encode($credentials));

        $remember_me = $request->has('remember_me') ? true : false;
        //codigo para insertar nuevos usuarios
        /*$user = User::create([
            'name' => 'admin',
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);*/

        /*Log::info("******** ");
        Log::info(!Auth::attempt(['email'=>$request->input('email'), 'password'=>$request->input('password')]));
        Log::info("request::: ");*/
        Log::info(!Auth::attempt(['email'=>$request->input('email'), 'password'=>$request->input('password')]));

        if (!Auth::attempt(['email'=>$request->input('email'), 'password'=>$request->input('password')], $remember_me)) {
            // return response()->json([
            // 'message' => 'Unauthorized'
            // ], 401);
            Log::info("Salio mal");
            return redirect()->to('login')->withErrors('Usuario o contraseña incorrectas');
        } else {
            $user = $request->user();



            $request->session()->put('userSession', json_encode($user));
            //Log::info(json_encode($usuario));
            //$this->new_action_system_audit([0 => null], [0 => null], 'Auth', $user->id, 'Auth', 'S');
            Log::info("inicio de session correcto");
            return redirect()->to('inicio');
        }


        // $user = $request->user();

        // Log::debug(json_encode($user));
        // $tokenResult = $user->createToken('Personal Access Token');
        // Log::info(json_encode($tokenResult));
        // Log::debug(json_encode($user));

        // $token = $tokenResult->token;
        // // if (isset($request->remember_me))
        // //     $token->expires_at = Carbon::now()->addWeeks(1);
        // Log::debug(json_encode($user));

        // $token->save();
        // return response()->json([
        //     'access_token' => $tokenResult->accessToken,
        //     'token_type' => 'Bearer',
        //     'expires_at' => Carbon::parse(
        //     $tokenResult->token->expires_at
        //     )->toDateTimeString()
        // ]);
    }
    function logout()
    {
        //$usuario = Usuario::where('ID_USER_ACCESS', Auth::user()->id)->first();
        //$this->new_action_system_audit([0 => null], [0 => null], 'Auth', $usuario->US_ID, 'Auth', 'L');
        Session::flush();
        Session::regenerate();
        Auth::logout();

        return redirect('/login');
    }

    function username()
    {
        return 'user_email';
    }

    function registrar(Request $request){

        Log::info('entro a registrar');
        Log::info('base de datos ' . env('DB_HOST'));
        Log::info(json_encode($request->all()));

        //codigo para insertar nuevos usuarios
        try{
            $user = User::create([      
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            return'Registro correcto';

        }catch(Exception $e){
            return 'Ocurrio un error al insertar el usuario '. $e;

        }
        


        

    }
}
