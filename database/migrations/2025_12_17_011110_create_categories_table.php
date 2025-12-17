<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('user');                 // session username
            $table->string('name');
            $table->string('icon_path')->nullable(); // lưu đường dẫn ảnh
            $table->timestamps();

            $table->index(['user']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
