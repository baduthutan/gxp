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
        Schema::create('charters', function (Blueprint $table) {
            $table->id();
            $table->enum('from_type', ['airport', 'city']);
            $table->string('from_master_area_id');
            $table->string('from_master_sub_area_id')->nullable();
            $table->string('to_master_area_id');
            $table->string('to_master_sub_area_id')->nullable();
            $table->string('vehicle_name');
            $table->string('vehicle_number');
            $table->integer('total_seat');
            $table->boolean('is_available')->default(false);
            $table->string('photo')->nullable();
            $table->decimal('price', 19, 2);
            $table->string('driver_contact')->nullable();
            $table->longText('notes')->nullable();
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
