<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function login(Request $request){
        $incomeData = $request->validate([
            'loginname' => 'required',
            'loginpassword' => 'required'
        ]);

        if (auth()->attempt(['name' => $incomeData['loginname'], 'password' => $incomeData['loginpassword']])) {
            $request->session()->regenerate();
        }
        return redirect('/');
    }  
    public function logout(){
            auth()->logout();
            return redirect('/');
        }
        //
        public function register(Request $request) {
            $incomeData = $request->validate([
                'name' => ['required', 'min:3', Rule::unique('users', 'name')],
                'email' => 'required',
                'password' => 'required'
            ]);
            //hash password
            $incomeData['password'] = bcrypt($incomeData['password']);
            $user = User::create($incomeData);
            auth()->login($user);

            return redirect('/');
        }
}


