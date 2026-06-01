<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentEmbeddingsTable extends Migration
{
    public function up()
    {
        Schema::create('student_embeddings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->longText('descriptor');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_embeddings');
    }
}
