<?php

namespace App\Http\Controllers;

//use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
//use Illuminate\Support\Facades\Validator;
//use Illuminate\Support\Facades\Mail;

class CustomAuthController extends Controller
{
    protected $pin;
    public function index(): View
    {
        return view('auth.login');
    }

    //custom login
    public function customLogin(Request $request): RedirectResponse|Redirector
    {
        $validator = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);


        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')
                ->with('success', 'Signed in');
        }
        $validator['emailPassword'] = 'Email address or password is incorrect.';
        return redirect("login")->withErrors($validator);
    }

    //custom register view
    public function registration(): View|JsonResponse
    {
        return view('auth.register');
    }

    //custom register page
    public function customRegistration(Request $request): RedirectResponse|Redirector|JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4',
        ]);

        $data = $request->all();
        $check = $this->create($data);

        //check for existing user
//        if ($check) {
//            $verify2 = DB::table('password_resets')->where([
//                ['email', $request->email]
//            ]);
//
//            if ($verify2->exists()) {
//                $verify2->delete();
//            }
//            $this->pin = rand(100000, 999999);
//            DB::table('password_resets')
//                ->insert(
//                    [
//                        'email' => $request->all()['email'],
//                        'token' => $this->pin,
//                        'created_at'=>now()
//                    ]
//                );
//            Mail::to($request->email)->send(new VerifyEmail($this->pin));
//        }
        //
        return redirect("auth.login")->with('Success', 'You have signed-in');
//        return new JsonResponse(['success'=>true,'message '=>'You have signed in'],200);

    }

    //custom create new User
    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }

    public function dashboard(): View|Redirector|RedirectResponse|JsonResponse
    {
        if (Auth::check()) {
            return view('auth.dashboard');
//            return new JsonResponse(['success'=>true,'message '=>'Inside Dashboard'],200);
        }
        return redirect("auth.login")->with('error', 'You are not allowed to access');
//        return new JsonResponse(['success'=>false,'message '=>'Yor are not allowed to access'],403);

    }

    // Forget password
    public function showForgetPassword(): Redirector
    {
        return redirect('forget.password.get');
    }
    public function signOut(): Redirector
    {
        $user = Auth::user();
//        $user->tokens()->delete();
        Session::flush();
        Auth::logout();
        return redirect('login');
    }

//     Verify email
//    public function verifyEmail(Request $request): RedirectResponse|Redirector|JsonResponse
//    {
//        $validatedData = $request->validate([
//            'token' => 'required',
//        ]);
//
//        //check for existing token
//        $tokenData = DB::table('temp_token_email')
//            ->where('token', $validatedData['token'])
//            ->first();
//
//
//        if (!$tokenData) {
//            return redirect()->back()->with('error', 'Invalid token');
////            return new JsonResponse(['success'=>false,'message '=>'Token invalid'],498);
//
//        }
////get user associated with email data
//        $user = User::where('email', $tokenData->email)->first();
//
//        if (!$user) {
//            return redirect()->back()->with('error', 'User not found');
////            return new JsonResponse(['success'=>false,'message '=>'User not found'],400);
//
//        }else{
//
//            $user->email_verified_at = now();
//            $user->save();
//
//            //delete token after verification
//            DB::table('password_resets')->where('email', $tokenData->email)->delete();
//            return redirect('auth.dashboard')->with('Success', 'Your email is verified');
////                return new JsonResponse(['success'=>true,'message '=>'Email is verified'],200);
//        }
//
//    }

    //resend of the pin
//    public function resendPin(Request $request) : JsonResponse
//    {
//        $validator = Validator::make($request->all(), [
//            'email' => ['required', 'string', 'email', 'max:255'],
//        ]);
//
//        if ($validator->fails()) {
//            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
//        }
//
//        $verify =  DB::table('temp_token_email')->where([
//            ['email', $request->all()['email']]
//        ]);
//
//        if ($verify->exists()) {
//            $verify->delete();
//        }
//
//        $token = random_int(100000, 999999);
//        $password_reset = DB::table('temp_token_email')->insert([
//            'email' => $request->all()['email'],
//            'token' =>  $token,
//            'created_at' => now()
//        ]);
//
//        if ($password_reset) {
//            Mail::to($request->all()['email'])->send(new VerifyEmail($token));
//
//            return new JsonResponse(
//                [
//                    'success' => true,
//                    'message' => "A verification mail has been resent"
//                ],
//                200
//            );
//        }else{
//            return new JsonResponse(
//                [
//                    'success' => false,
//                    'message' => "A verification mail cannot be sent"
//                ],
//                501
//            );
//        }
//    }
}
