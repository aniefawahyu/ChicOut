<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
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

            // $user = DB::table('accounts')
            //     ->where('username', $req->loginUsername)
            //     // ->where('password', $req->loginPassword)
            //     ->first();

            // kasir
            if ($req->loginUsername === 'kasir' && $req->loginPassword === 'kasir') {
                session(['user' => (object)[
                    'username' => 'kasir',
                    'role' => 'master'
                ]]);

                return redirect()->route('master-home');
            }

            if (Auth::attempt($credentials)) {
                $req->session()->regenerate();
                $user = Auth::user();
                // dd($user);

                // Cek role user untuk menentukan redirect
                if ($user->role === 'master') {
                    return redirect()->route('master-home');
                }
                return redirect('/ChicOut');
            } else {
                return redirect()->route('login')->with("pesan", "Username or Password incorrect.");
            }

            // // Jika user ditemukan
            // if ($user) {
            //     // Simpan data user ke session
            //     session(['user' => $user]);

            //     // Cek role user untuk menentukan redirect
            //     if ($user->role === 'master') {
            //         // dd($user);
            //         return redirect()->route('master-home');
            //     }
            //     return redirect('/ChicOut');
            // }


            // // if (Auth::attempt($credentials)) {
            // //     if (Auth::user()->role == "master") {
            // //         return redirect()->route('master-home');
            // //     }
            // //     return redirect()->route('home');
            // // }
            // else {
            //     return redirect()->route('login')->with("pesan", "Username or Password incorrect.");
            // }
        } else {
            $rules = [
                'username' => 'required',
                'display_name' => 'required',
                'password' => 'required',
                'confirmPassword' => 'required|same:password',
                'email' => 'required|email',
                'tel' => 'required|numeric',
                'address' => 'required',
            ];
            $newAcc = Account::create([
                "username" => $req->username,
                "display_name" => $req->display_name,
                "password" => $req->password,
                "email" => $req->email,
                "tel" => $req->tel,
                "address" => $req->address,
                "role" => "user",
            ]);
            return redirect()->route('login')->with('sukses', 'Successfully registered an account!');
        }
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

        $validator = Validator::make($req->all(), $rules, $messages)->validate();



        // return redirect()->route('login')->with('sukses', 'Successfully registered an account!');
        // }
    }

    public function logoutAccount()
    {
        if (Auth::check()) {
            Auth::logout();
        }
        return redirect()->route("home");
    }
}
