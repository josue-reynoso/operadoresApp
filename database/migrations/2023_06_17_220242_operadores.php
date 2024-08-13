<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\DB;

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
        DB::unprepared("CREATE TABLE `operadores`(
            `OP_ID` INT NOT NULL AUTO_INCREMENT,
            `OP_Name` VARCHAR(255) NOT NULL,
            `OP_APE` VARCHAR(255),
            `OP_Fch_Nac` DATE,
            `OP_Address` VARCHAR(255),
            `OP_Cel` VARCHAR(12),
            `OP_Email` VARCHAR(255),
            `OP_Status` TINYINT(1) DEFAULT 1 COMMENT 'Estado del operador, 1: activo, 0, inactivo',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`OP_ID`)
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
