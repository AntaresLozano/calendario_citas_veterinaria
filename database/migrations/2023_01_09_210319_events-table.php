<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// use App\Models\Event;

class EventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('cedula');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('mascota');
            $table->string('start')->unique();
            $table->string('fecha');
            $table->string('hora');
            $table->string('descripcion');
            $table->string('color');
            // $table->updated_at();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

