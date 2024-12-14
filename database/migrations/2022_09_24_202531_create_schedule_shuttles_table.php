<?php

use App\Models\Vehicle;
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
        Schema::create('schedule_shuttles', function (Blueprint $table) {
            $table->id();
            $table->enum('from_type', ['airport', 'city']);
            $table->string('from_master_area_id');
            $table->string('from_master_sub_area_id')->nullable(true);
            $table->string('to_master_area_id');
            $table->string('to_master_sub_area_id')->nullable(true);
            $table->string('vehicle_name');
            $table->string('vehicle_number');
            $table->time('time_departure');
            $table->boolean('is_active')->default(false);
            $table->string('photo')->nullable();
            $table->decimal('price', 19, 2);
            $table->string('driver_contact')->nullable();
            $table->longText('notes')->nullable();
            $table->integer('total_seat')->unsigned();
            $table->decimal('luggage_price', 19, 2)->unsigned();
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
        Schema::dropIfExists('schedules');
    }
};
