<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('is_anonymous')->default(false)->after('is_featured');
            $table->string('author_name')->nullable()->after('is_anonymous');
            $table->string('author_email')->nullable()->after('author_name');
            $table->string('author_mobile', 20)->nullable()->after('author_email');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['is_anonymous', 'author_name', 'author_email', 'author_mobile']);
        });
    }
};
