<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function getLoginPage()
    {
        return view('login-register');
    }

    public function loginRegister(Request $req)
    {
        if ($req->has("login")) {
            return $this->handleLogin($req);
        } else {
            return $this->handleRegister($req);
        }
    }

    private function handleLogin(Request $req)
    {
        $rules = [
            'loginUsername' => 'required',
            'loginPassword' => 'required',
        ];

        $messages = [
            'loginUsername.required' => 'Username is required.',
            'loginPassword.required' => 'Password is required.',
        ];

        $req->validate($rules, $messages);

        $credentials = [
            'username' => $req->loginUsername,
            'password' => $req->loginPassword
        ];

        if (Auth::attempt($credentials)) {
            $req->session()->regenerate();
            $user = Auth::user();

            return $user->role === 'master' 
                ? redirect()->route('master-home')
                : redirect('/ChicOut');
        }

        return redirect()
            ->route('login')
            ->with("pesan", "Username or Password incorrect.");
    }

    private function handleRegister(Request $req)
    {
        $rules = [
            'username' => 'required|unique:accounts,username',
            'display_name' => 'required',
            'password' => 'required|min:6',
            'confirmPassword' => 'required|same:password',
            'email' => 'required|email|unique:accounts,email',
            'tel' => 'required|numeric',
            'address' => 'required',
        ];

        $messages = [
            'username.required' => 'Username is required.',
            'username.unique' => 'Username is already in use.',
            'display_name.required' => 'Display name is required.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'confirmPassword.required' => 'Confirm Password is required.',
            'confirmPassword.same' => 'Confirm Password must match the password.',
            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format.',
            'email.unique' => 'Email is already registered.',
            'tel.required' => 'Phone number is required.',
            'tel.numeric' => 'Phone number must be numeric.',
            'address.required' => 'Address is required.',
        ];

        $validator = Validator::make($req->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->route('login')
                ->withErrors($validator)
                ->withInput();
        }

        Account::create([
            "username" => $req->username,
            "display_name" => $req->display_name,
            "password" => Hash::make($req->password),
            "email" => $req->email,
            "tel" => $req->tel,
            "address" => $req->address,
            "role" => "user",
        ]);

        return redirect()
            ->route('login')
            ->with('sukses', 'Successfully registered an account!');
    }

    public function logoutAccount()
    {
        if (Auth::check()) {
            Auth::logout();
            Session::flush();
            return redirect()
                ->route("home")
                ->with('sukses', 'Successfully logged out!');
        }
        
        return redirect()->route("home");
    }
}