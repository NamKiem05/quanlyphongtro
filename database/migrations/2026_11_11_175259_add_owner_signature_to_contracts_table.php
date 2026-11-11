<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE contracts
            MODIFY status ENUM(
                'draft',
                'waiting_signature',
                'waiting_owner_signature',
                'active',
                'expired',
                'terminated'
            ) NOT NULL DEFAULT 'draft'
        ");

        Schema::table('contracts', function (Blueprint $table) {
            $table->string('owner_signature_path')->nullable();
            $table->dateTime('owner_signed_at')->nullable();
            $table->string('owner_signed_ip', 45)->nullable();
            $table->text('owner_user_agent')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn([
                'owner_signature_path',
                'owner_signed_at',
                'owner_signed_ip',
                'owner_user_agent',
            ]);
        });

        DB::statement("
            ALTER TABLE contracts
            MODIFY status ENUM(
                'draft',
                'waiting_signature',
                'active',
                'expired',
                'terminated'
            ) NOT NULL DEFAULT 'draft'
        ");
    }
};
