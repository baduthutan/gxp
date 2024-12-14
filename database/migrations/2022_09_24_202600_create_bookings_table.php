<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number');
            $table->integer('schedule_id');
            $table->integer('from_master_area_id');
            $table->string('from_master_area_name');
            $table->integer('from_master_sub_area_id')->nullable();
            $table->string('from_master_sub_area_name')->nullable();
            $table->integer('to_master_area_id');
            $table->string('to_master_area_name');
            $table->integer('to_master_sub_area_id')->nullable();
            $table->string('to_master_sub_area_name')->nullable();
            $table->string('vehicle_name');
            $table->string('vehicle_number');
            $table->dateTime('datetime_departure');
            $table->enum('schedule_type', ['shuttle', 'charter']);
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('customer_phone');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->integer('qty_adult');
            $table->integer('qty_baby');
            $table->decimal('base_price', 19, 2)->default(0);
            $table->decimal('total_base_price', 19, 2)->default(0);
            $table->string('flight_number')->nullable();
            $table->longText('flight_info')->nullable();
            $table->longText('notes')->nullable();
            $table->integer('luggage_qty')->default(0);
            $table->decimal('luggage_price', 19, 2)->default(0);
            $table->integer('overweight_luggage_qty')->default(0);
            $table->decimal('overweight_luggage_price', 19, 2)->default(0);
            $table->boolean('special_request')->default(false);
            $table->integer('special_area_id')->nullable();
            $table->longText('special_area_detail')->nullable();
            $table->string('regional_name')->nullable();
            $table->decimal('extra_price', 19, 2)->default(0);
            $table->foreignId('voucher_id')->nullable(true)->constrained('vouchers')->nullOnDelete()->cascadeOnUpdate();
            $table->decimal('promo_price', 19, 2)->default(0);
            $table->decimal('sub_total_price', 19, 2)->default(0);
            $table->decimal('fee_price', 19, 2)->default(0);
            $table->decimal('total_price', 19, 2)->default(0);
            $table->enum('booking_status', ['pending', 'active', 'expired']);
            $table->enum('payment_status', ['waiting', 'paid', 'failed']);
            $table->string('payment_method')->nullable();
            $table->string('payment_token')->nullable();
            $table->decimal('total_payment', 19, 2)->default(0.00);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
