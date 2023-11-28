<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create("doctors", function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger("clinic_id");
      $table->string("email")->unique();
      $table->string("password")->nullable();
      $table->string("name");
      $table->string("phone");
      $table->string("address");
      $table->string("city");
      $table->string("state");
      $table->string("zip");
      $table->string("license");
      //   $table->string("specialty");
      $table->string("image");
      $table->timestamp("email_verified_at")->nullable();
      $table->rememberToken();
      $table->timestamps();

      $table
        ->foreign("clinic_id")
        ->references("id")
        ->on("users")
        ->onDelete("cascade");
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists("doctors");
  }
};
