
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contract_services', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contract_id')
                ->constrained('contracts')
                ->cascadeOnDelete();

            $table->foreignId('service_id')
                ->nullable()
                ->constrained('services')
                ->nullOnDelete();

            // Snapshot thông tin dịch vụ tại thời điểm lập hợp đồng
            $table->string('service_name', 100);

            $table->enum('type', [
                'metered',
                'fixed'
            ])->default('fixed');

            $table->string('unit', 30)->nullable();

            $table->decimal('quantity', 10, 2)->default(1);

            $table->decimal('unit_price', 12, 2)->default(0);

            $table->timestamps();

            $table->unique(['contract_id', 'service_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_services');
    }
};
