<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sort_name');
            $table->string('type');
            $table->string('gender');
            $table->timestamps('begin_end_date');
            $table->string('IPI_code');
            $table->string('ISNI_code');
            $table->string('alias');
            $table->string('MBID');
            $table->string('comment');
            $table->string('annatation');
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
        Schema::dropIfExists('artists');
    }
}
