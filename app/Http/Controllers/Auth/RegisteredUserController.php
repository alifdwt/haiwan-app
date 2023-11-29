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
use Intervention\Image\Facades\Image;
use App\Http\Controllers\CloudinaryStorage;

class RegisteredUserController extends Controller
{
  /**
   * Display the registration view.
   */
  public function create(): View
  {
    return view("auth.register");
  }

  /**
   * Handle an incoming registration request.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(Request $request): RedirectResponse
  {
    $request->validate([
      "name" => ["required", "string", "max:255"],
      "email" => [
        "required",
        "string",
        "lowercase",
        "email",
        "max:255",
        "unique:" . User::class,
      ],
      "password" => ["required", "confirmed", Rules\Password::defaults()],
      "phone" => ["required", "string", "max:255"],
      "image_1" => [
        "required",
        // "image",
        "mimes:jpeg,png,jpg,gif,svg",
        "max:2048",
      ],
      "image_2" => ["mimes:jpeg,png,jpg,gif,svg", "max:2048"],
      "image_3" => ["mimes:jpeg,png,jpg,gif,svg", "max:2048"],
      "image_4" => ["mimes:jpeg,png,jpg,gif,svg", "max:2048"],
      "city" => ["required", "string", "max:255"],
      "state" => ["required", "string", "max:255"],
      "zip" => ["required", "string", "max:255"],
      "description" => ["required", "string"],
      "opening_hours_from" => ["required", "string", "max:255"],
      "opening_hours_to" => ["required", "string", "max:255"],
      "opening_days" => ["required", "array"],
      "location" => ["required", "string", "max:255"],
    ]);

    $images = [];
    $imageFields = ["image_1", "image_2", "image_3", "image_4"];

    foreach ($imageFields as $field) {
      if ($request->hasFile($field)) {
        $image = $request->file($field);
        $result = CloudinaryStorage::upload(
          $image->getRealPath(),
          $image->getClientOriginalName(),
        );

        $images[$field] = $result;
      }
    }

    $user = User::create([
      "name" => $request->name,
      "email" => $request->email,
      "password" => Hash::make($request->password),
      "phone" => $request->phone,
      "city" => $request->city,
      "state" => $request->state,
      "zip" => $request->zip,
      "description" => $request->description,
      "opening_hours" =>
        $request->opening_hours_from . " - " . $request->opening_hours_to,
      "opening_days" => json_encode($request->opening_days),
      "location" => $request->location,
      "image_1" => $images["image_1"],
      "image_2" => $images["image_2"] ?? null,
      "image_3" => $images["image_3"] ?? null,
      "image_4" => $images["image_4"] ?? null,
    ]);

    event(new Registered($user));

    Auth::login($user);

    return redirect(RouteServiceProvider::DASHBOARD);
  }
}
