<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
  use HasFactory;
  protected $fillable = [
    "clinic_id",
    "name",
    "phone",
    "address",
    "city",
    "state",
    "zip",
    "license",
    // "specialty",
    "image",
    "email",
    "password",
    "remember_token",
    "email_verified_at",
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = ["password", "remember_token"];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    "email_verified_at" => "datetime",
    "password" => "hashed",
  ];

  public function clinic()
  {
    return $this->belongsTo(User::class, "clinic_id", "id");
  }
  public function specialities()
  {
    return $this->belongsToMany(
      Speciality::class,
      "doctor_specialities"
    )->withTimestamps();
  }
}
