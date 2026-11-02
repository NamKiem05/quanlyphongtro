
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contract_id')
                ->constrained('contracts')
                ->restrictOnDelete();

            $table->string('invoice_code')->unique();
            $table->date('billing_month');
            $table->date('due_date');

            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);

            $table->enum('status', [
                'unpaid',
                'partial',
                'paid',
                'overdue'
            ])->default('unpaid');

            $table->text('note')->nullable();

            $table->timestamps();

            $table->unique(['contract_id', 'billing_month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
