<?php

use App\Models\Agent;
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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            // $table->foreignIdFor(Agent::class);
            $table->string('name');
            $table->string('code');
            $table->date('date_start');
            $table->date('date_expired');
            $table->enum('discount_type', ['percentage', 'value']);
            $table->decimal('discount_value', 19, 2, true);
            $table->boolean('is_active');
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
        Schema::dropIfExists('vouchers');
    }
};
