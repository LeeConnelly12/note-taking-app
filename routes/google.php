<?php

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

Route::get('/auth/redirect', function () {
    return Socialite::driver('google')->redirect();
})->name('google.redirect');

Route::get('/auth/callback', function () {
    $googleUser = Socialite::driver('google')->user();

    $user = User::updateOrCreate([
        'google_id' => $googleUser->getId(),
    ], [
        'name' => $googleUser->getName(),
        'email' => $googleUser->getEmail(),
    ]);

    Auth::login($user);

    return to_route('home');
})->name('google.callback');
