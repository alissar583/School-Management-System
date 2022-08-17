<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('f_name');
            $table->string('l_name');
            $table->string('email');
            $table->string('code');
            $table->text('picture')->nullable();
            $table->date('birthdate');
            $table->foreignId('parent_id')->constrained('parents')->cascadeOnDelete();
            $table->foreignId('blood_id')->constrained('bloods')->cascadeOnDelete();
            $table->foreignId('gender_id')->constrained('genders')->cascadeOnDelete();
            $table->foreignId('nationality_id')->constrained('nationality')->cascadeOnDelete();
            $table->foreignId('religion_id')->constrained('religtions')->cascadeOnDelete();
            $table->foreignId('grade_id')->constrained('grades')->cascadeOnDelete();
            $table->foreignId('class_classroom_id')->constrained('claass_classrooms')->cascadeOnDelete();
//            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('address_id')->constrained('addresses')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
