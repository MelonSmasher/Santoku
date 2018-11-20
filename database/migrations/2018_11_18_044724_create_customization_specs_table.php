<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomizationSpecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customization_specs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('vm_template_id');
            $table->unsignedInteger('network_id');
            $table->string('vm_name_prefix')->nullable();
            $table->string('node_name_postfix')->nullable();
            $table->longText('provision_command');
            $table->timestamps();
            $table->foreign('vm_template_id')->references('id')->on('vm_templates')->onDelete('cascade');
            $table->foreign('network_id')->references('id')->on('networks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customization_specs', function (Blueprint $table) {
            $table->dropForeign('customization_specs_vm_template_id_foreign');
            $table->dropForeign('customization_specs_network_id_foreign');
        });
        Schema::dropIfExists('customization_specs');
    }
}
