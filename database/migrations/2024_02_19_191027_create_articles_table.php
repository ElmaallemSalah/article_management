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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string("image");
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade");
            $table->foreignId("category_id")->constrained("categories")->onDelete("cascade");
           
            
            //discription
            $table->text('description');
            
    

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropForeign('user_id');
        Schema::dropForeign('category_id');
        Schema::dropIfExists('articles');

    }
};
