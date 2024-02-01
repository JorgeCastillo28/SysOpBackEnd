<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Auth\AuthController;
use App\Models\Warehouse;

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
   
    protected $maxAttempts = 5; // Default is 5
    protected $decayMinutes = 1; // Default is 1

    use AuthenticatesUsers;

    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Where to redirect users after login.
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

    public function username()
    {
        return 'username';
    }
  
    protected function authenticated(Request $request, $user)
    {
        $datetime = Carbon::now()->toDateTimeString();

        $branches = $user->warehouses->where('active', true);

        if ($branches->count() == 1):

            $route = route('dashboard');

            return response()->json([
                'error'     => false,
                'redirect'  => $route,
                'msg'       => 'Ha iniciado sesión correctamente'
            ]);

        endif;
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect('/login');
    }
}
