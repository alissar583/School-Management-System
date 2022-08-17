<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ClaassClassroomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claass_classrooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('claasses', 'id')->cascadeOnDelete();
            $table->foreignId('classroom_id')->constrained('classrooms', 'id')->cascadeOnDelete();
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
        Schema::dropIfExists('claass_classrooms');
    }
}
