<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('nodes', 'cover_image')) {
            Schema::table('nodes', function (Blueprint $table) {
                $table->text('cover_image')->nullable()->after('user_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('nodes', 'cover_image')) {
            Schema::table('nodes', function (Blueprint $table) {
                $table->dropColumn('cover_image');
            });
        }
    }
};
