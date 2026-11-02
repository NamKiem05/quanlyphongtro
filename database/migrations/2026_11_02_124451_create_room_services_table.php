
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_services', function (Blueprint $table) {
            $table->id();

            $table->foreignId('room_id')
                ->constrained('rooms')
                ->cascadeOnDelete();

            $table->foreignId('service_id')
                ->constrained('services')
                ->restrictOnDelete();

            $table->decimal('quantity', 10, 2)->default(1);
            $table->decimal('unit_price', 12, 2);

            $table->timestamps();

            $table->unique(['room_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_services');
    }
};
