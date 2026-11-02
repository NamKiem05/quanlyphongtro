
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contract_tenants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contract_id')
                ->constrained('contracts')
                ->cascadeOnDelete();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->boolean('is_representative')->default(false);

            $table->timestamps();

            $table->unique(['contract_id', 'tenant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_tenants');
    }
};
