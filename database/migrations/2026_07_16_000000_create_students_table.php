<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nipd')->unique();
            $table->string('nisn')->nullable()->unique();
            $table->string('student_nik')->nullable()->unique();
            $table->string('level');
            $table->string('class_name');
            $table->string('gender');
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('religion')->nullable();
            $table->text('address')->nullable();
            $table->unsignedTinyInteger('child_order')->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_nik')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_nik')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('student_photo_path')->nullable();
            $table->string('family_card_photo_path')->nullable();
            $table->string('parent_id_card_photo_path')->nullable();
            $table->string('status')->default('aktif');
            $table->timestamps();

            $table->index(['status', 'level', 'class_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
