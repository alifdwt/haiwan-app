<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialitySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table("specialities")->insert([
      [
        "id" => 1,
        "speciality" => "Cardiology",
        "description" => "Specialization in heart problems of animals",
        "created_at" => now(),
        "updated_at" => now(),
      ],
      [
        "id" => 2,
        "speciality" => "Neurology",
        "description" => "Specialization in nerve problems of animals",
        "created_at" => now(),
        "updated_at" => now(),
      ],
      [
        "id" => 3,
        "speciality" => "Veterinary Surgery",
        "description" => "Specialization in animal surgery",
        "created_at" => now(),
        "updated_at" => now(),
      ],
      [
        "id" => 4,
        "speciality" => "Ophthalmology",
        "description" => "Specialization in eye problems of animals",
        "created_at" => now(),
        "updated_at" => now(),
      ],
      [
        "id" => 5,
        "speciality" => "Pet Medicine",
        "description" =>
          "Specialization in pet animals like cats, dogs, and rabbits",
        "created_at" => now(),
        "updated_at" => now(),
      ],
      [
        "id" => 6,
        "speciality" => "Livestock Medicine",
        "description" =>
          "Specialization in livestock animals like goats, sheep, cows, and buffaloes",
        "created_at" => now(),
        "updated_at" => now(),
      ],
      [
        "id" => 7,
        "speciality" => "Exotic Animal Medicine",
        "description" =>
          "Specialization in exotic animals like snakes and birds",
        "created_at" => now(),
        "updated_at" => now(),
      ],
    ]);
  }
}
