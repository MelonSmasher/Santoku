<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeploySessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deploy_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customization_spec_id');
            $table->string('token')->unique();
            $table->timestamps();
            $table->foreign('customization_spec_id')->references('id')->on('customization_specs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deploy_sessions', function (Blueprint $table) {
            $table->dropForeign('deploy_sessions_customization_spec_id_foreign');
        });
        Schema::dropIfExists('deploy_sessions');
    }
}
