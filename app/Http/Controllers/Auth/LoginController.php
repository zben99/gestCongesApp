<?php
$credentials = $request->only('email', 'password');

if (Auth::attempt($credentials, $request->filled('remember'))) {
    $request->session()->regenerate();
    return redirect()->intended('dashboard');
}

return redirect()->back()->with('error', 'Identifiants incorrects.');
