<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use App\Models\Speciality;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\CloudinaryStorage;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Mail;

class DoctorController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    // $clinics = User::orderBy("id")->get();
    // return view("auth.register-doctor", compact("clinics"));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $clinics = User::orderBy("id")->get();
    $specialities = Speciality::orderBy("id")->get();
    return view("auth.register.doctor", compact("clinics", "specialities"));
  }

  /**
   * Handle an incoming registration request.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(Request $request)
  {
    $request->validate([
      "name" => ["required", "string", "max:255"],
      "email" => ["required", "string", "email", "max:255", "unique:users"],
      "password" => ["required", "confirmed", Rules\Password::defaults()],
      "clinic_id" => ["required", "exists:users,id"],
      "phone" => ["required", "string", "max:255"],
      "address" => ["required", "string", "max:255"],
      "city" => ["required", "string", "max:255"],
      "state" => ["required", "string", "max:255"],
      "zip" => ["required", "string", "max:255"],
      "license" => ["required", "string", "max:255"],
      "image" => ["required", "image", "mimes:jpeg,png,jpg,gif,svg"],
      "specialities" => ["required", "array", "max:255"],
    ]);

    $token = Str::random(32);

    $image = $request->file("image");
    $result = CloudinaryStorage::upload(
      $image->getRealPath(),
      $image->getClientOriginalName(),
    );

    $doctor = Doctor::create([
      "name" => $request->name,
      "email" => $request->email,
      "password" => Hash::make($request->password),
      "clinic_id" => $request->clinic_id,
      "phone" => $request->phone,
      "address" => $request->address,
      "city" => $request->city,
      "state" => $request->state,
      "zip" => $request->zip,
      "license" => $request->license,
      "image" => $result,
      "specialty" => $request->specialty,
      "remember_token" => $token,
    ]);

    $doctor->specialities()->attach($request->specialities);

    Mail::send(
      "emails.emailVerificationEmail",
      ["token" => $token, "name" => $request->name],
      function ($message) use ($request) {
        $message
          ->to($request->email, $request->name)
          ->subject("Email Verification");
      },
    );

    event(new Registered($doctor));

    Auth::login($doctor);

    return redirect(RouteServiceProvider::HOME);
  }

  /**
   * Display the specified resource.
   */
  public function show(Doctor $doctor)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Doctor $doctor)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Doctor $doctor)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id): RedirectResponse
  {
    $doctor = User::findOrFail($id);
    $doctor->delete();

    return redirect()
      ->back()
      ->with("success", "Doctor deleted successfully.");
  }

  public function verifyEmail($token)
  {
    $verifyDoctor = Doctor::where("remember_token", $token)->firstOrFail();
    $message = "Sorry, your email cannot be verified. Please try again.";

    if ($verifyDoctor) {
      if ($verifyDoctor->email_verified_at === null) {
        $verifyDoctor->email_verified_at = now();
        $verifyDoctor->remember_token = null;
        $verifyDoctor->save();
        $message = "Your email has been successfully verified.";
      } else {
        $message = "Your email has already been verified.";
      }
    }

    return redirect()
      ->route("login.doctor")
      ->with("success", $message);
  }
}
