<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVmTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vm_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('operating_system_id');
            $table->string('name');
            $table->string('vm_template_name');
            $table->string('image_url')->nullable();
            $table->timestamps();
            $table->foreign('operating_system_id')->references('id')->on('operating_systems')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vm_templates', function (Blueprint $table) {
            $table->dropForeign('vm_templates_operating_system_id_foreign');
        });
        Schema::dropIfExists('vm_templates');
    }
}
