<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->after('user_id');
            $table->string('phone_number')->nullable()->after('total_amount');
            $table->text('address')->nullable()->after('phone_number');
        });

        Schema::table('product_payments', function (Blueprint $table) {
            $table->foreignId('order_id')
                ->nullable()
                ->after('product_id')
                ->constrained('orders')
                ->nullOnDelete();
        });

        Schema::table('book_purchases', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->unique()->after('book_id');
            $table->decimal('amount', 10, 2)->default(0)->after('operator');
            $table->json('gateway_response')->nullable()->after('status');
            $table->string('payment_type')->default('book_purchase')->after('gateway_response');
            $table->timestamp('paid_at')->nullable()->after('payment_type');
        });

        Schema::table('course_purchases', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->unique()->after('course_id');
            $table->decimal('amount', 10, 2)->default(0)->after('operator');
            $table->json('gateway_response')->nullable()->after('status');
            $table->string('payment_type')->default('course_purchase')->after('gateway_response');
            $table->timestamp('paid_at')->nullable()->after('payment_type');
        });

        \DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','paid','shipped','delivered','completed','cancelled') DEFAULT 'pending'");
        \DB::statement("ALTER TABLE course_purchases MODIFY status ENUM('pending','approved','rejected','failed','cancelled') DEFAULT 'pending'");
    }

    public function down(): void
    {
        \DB::statement("ALTER TABLE course_purchases MODIFY status ENUM('pending','approved','rejected') DEFAULT 'pending'");
        \DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','paid','shipped','completed','cancelled') DEFAULT 'pending'");

        Schema::table('course_purchases', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'amount', 'gateway_response', 'payment_type', 'paid_at']);
        });

        Schema::table('book_purchases', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'amount', 'gateway_response', 'payment_type', 'paid_at']);
        });

        Schema::table('product_payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('order_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'phone_number', 'address']);
        });
    }
};
