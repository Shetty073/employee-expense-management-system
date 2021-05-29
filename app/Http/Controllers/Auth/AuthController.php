<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    protected $redirectTo = '/';

    public function index()
    {
        $users = User::all();

        return view('auth.index', compact('users'));
    }

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $request->flashExcept('password');

        $credentials = $request->only('email', 'password');

        // Check if the user is marked as inactive, if they are then do not login and return back with errors
        $user = User::where('email', $request->input('email'))->first();
        if($user) {
            if(!$user->active) {
                return back()->withErrors([
                    'email' => 'Your account is not active.',
                    'password' => 'Please contact administrator for access.',
                ]);
            }
        }

        $remember = false;

        if ($request->has('remember')) {
            $remember = true;
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
            'password' => 'The provided credentials do not match our records.',
        ]);
    }

    public function register()
    {
        return view('auth.register');
    }


    public function store(Request $request)
    {
        $request->flashExcept(['password', 'password_confirmation']);

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'is_admin' => true,
        ]);

        return redirect(route('auth.index'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findorfail($id);

        return view('auth.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->flashExcept(['aadhar_card_scan', 'photo', 'resume']);

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $user = User::findorfail($id);
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);

        if($request->input('password') != null) {
            $this->validate($request, [
                'password' => 'required|confirmed',
            ]);

            $user->update([
                'password' => Hash::make($request->input('password')),
            ]);
        }

        return redirect(route('auth.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findorfail($id);
        $user->active = false;
        $user->save();

        return response()->json([
            'process' => 'success',
        ]);
    }

    public function activate($id)
    {
        $user = User::findorfail($id);
        $user->active = true;
        $user->save();

        return response()->json([
            'process' => 'success',
        ]);
    }
}
