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
        if (!Schema::hasTable('pages')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->bigInteger('parent_page_id')->nullable()->after('id');
                $table->string('url')->nullable();
                $table->string('type')->nullable();
                $table->string('is_dropdown', 1)->nullable();
                $table->bigInteger('orderNumber')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('parent_page_id');
            $table->dropColumn('url');
            $table->dropColumn('type');
            $table->dropColumn('is_dropdown');
            $table->dropColumn('orderNumber');
        });
    }
};
