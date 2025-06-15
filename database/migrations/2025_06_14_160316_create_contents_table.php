<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
public function up()
{
    Schema::create('contents', function (Blueprint $table) {
        $table->id();
        $table->foreignId('module_id')->constrained()->onDelete('cascade');
        $table->string('type'); // text, image, video, link, etc.
        $table->text('data'); // actual content
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
