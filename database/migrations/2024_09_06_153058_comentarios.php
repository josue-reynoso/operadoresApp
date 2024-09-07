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
        //
        DB::unprepared("CREATE TABLE `comentarios`(  
                        `COM_ID` INT(11) NOT NULL AUTO_INCREMENT,
                        `COM_OP_ID` INT(11) NOT NULL,
                        `COM_DES` VARCHAR(520),
                        `COM_DATE` DATE,
                        PRIMARY KEY (`COM_ID`)
                        );");
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
};
