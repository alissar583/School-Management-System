<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes', 'id')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students', 'id')->cascadeOnDelete();
            $table->integer('mark');
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
        Schema::dropIfExists('quiz_marks');
    }
}
