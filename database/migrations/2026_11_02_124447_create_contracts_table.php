
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('room_id')
                ->constrained('rooms')
                ->restrictOnDelete();

            $table->date('start_date');
            $table->date('end_date')->nullable();

            $table->decimal('rent_price', 12, 2);
            $table->decimal('deposit_amount', 12, 2)->default(0);

            $table->unsignedTinyInteger('billing_day')->default(1);

            $table->enum('status', [
                'draft',
                'waiting_signature',
                'active',
                'expired',
                'terminated'
            ])->default('draft');

            $table->dateTime('signed_at')->nullable();
            $table->string('pdf_path')->nullable();
            $table->string('signed_pdf_path')->nullable();
            $table->text('terms')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
