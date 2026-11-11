
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contract_signatures', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contract_id')
                ->constrained('contracts')
                ->cascadeOnDelete();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            // Token bảo mật của đường dẫn ký
            $table->string('token_hash', 64)->unique();

            // Thời hạn ký
            $table->dateTime('expires_at')->nullable();

            // Đường dẫn ảnh chữ ký
            $table->string('signature_path')->nullable();

            // Trạng thái ký
            $table->enum('status', [
                'pending',
                'signed',
                'expired',
            ])->default('pending');

            // Thời điểm và thông tin xác nhận ký
            $table->dateTime('signed_at')->nullable();
            $table->string('signed_ip', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            // Mỗi người thuê chỉ có một bản ghi ký trong một hợp đồng
            $table->unique(['contract_id', 'tenant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_signatures');
    }
};
