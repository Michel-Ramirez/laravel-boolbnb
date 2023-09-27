<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['string', 'max:255', "nullable"],
            "surname" => ['string', 'max:255', "nullable"],
            "birth_date" => ['date', "nullable"],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            "name.max" => "Il nome deve contenere al massimo 255 caratteri",
            "surname.max" => "Il cognome deve contenere al massimo 255 caratteri",
            "birth_date.date" => "Il valore non è una data",
            "email.required" => "L'email è obbligatoria",
            "email.email" => "L'email non è valida",
            "email.max" => "L'email deve contenere al massimo 255 caratteri",
            "email.unique" => "L'email inserita già è in uso",
            "password.required" => "La password è obbligatoria",
            "password.confirmed" => "Le password non coincidono",
        ]);

        $user = User::create([
            'name' => $request->name,
            "surname" => $request->surname,
            "birth_date" => $request->birth_date,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
