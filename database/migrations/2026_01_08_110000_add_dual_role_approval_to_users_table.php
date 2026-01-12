<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('allow_dual_role')->default(false)->after('role');
            $table->json('additional_roles')->nullable()->after('allow_dual_role');
            $table->foreignId('dual_role_approved_by')->nullable()->constrained('users')->after('additional_roles');
            $table->timestamp('dual_role_approved_at')->nullable()->after('dual_role_approved_by');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['dual_role_approved_by']);
            $table->dropColumn(['allow_dual_role', 'additional_roles', 'dual_role_approved_by', 'dual_role_approved_at']);
        });
    }
};