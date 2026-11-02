
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meter_readings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('room_id')
                ->constrained('rooms')
                ->restrictOnDelete();

            $table->foreignId('service_id')
                ->constrained('services')
                ->restrictOnDelete();

            $table->date('reading_month');
            $table->decimal('old_index', 12, 2)->default(0);
            $table->decimal('new_index', 12, 2);
            $table->decimal('usage', 12, 2);

            $table->timestamps();

            $table->unique([
                'room_id',
                'service_id',
                'reading_month'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meter_readings');
    }
};
