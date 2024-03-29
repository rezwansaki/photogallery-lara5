<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('project_name')->default('PhotoGallary');
            $table->string('email_to_reset_password')->default('example@example.com');
            $table->string('password_of_email')->default('yourpassword');
            $table->integer('max_uploaded_file_size')->default('900');
            $table->integer('total_images_to_display')->default('20');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
