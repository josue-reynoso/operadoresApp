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
        DB::unprepared("CREATE TABLE `rutas`(  
                        `R_ID` INT NOT NULL AUTO_INCREMENT,
                        `R_Color` VARCHAR(255),
                        `R_Numero` INT,
                        `R_Letra` VARCHAR(255),
                        `R_Status` TINYINT(1) DEFAULT 1,
                        PRIMARY KEY (`R_ID`)
                        ) ENGINE=INNODB;
                        ");
        DB::unprepared("ALTER TABLE `operadores`   
                ADD COLUMN `OP_ID_R` INT(11) NOT NULL 
                 COMMENT 'ruta actual' AFTER `OP_Status`;");

        DB::unprepared("CREATE TABLE `documentos`(  
                        `DC_ID` INT NOT NULL AUTO_INCREMENT,
                        `DC_Nombre` VARCHAR(255),
                        `DC_Path` VARCHAR(255),
                        `DC_Ext` VARCHAR(255),
                        `DC_Type` VARCHAR(255),
                        PRIMARY KEY (`DC_ID`)
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
