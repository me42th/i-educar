<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateAlimentosUnidadeFaixaEtariaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            '
                SET default_with_oids = true;
                
                CREATE SEQUENCE alimentos.unidade_faixa_etaria_idfeu_seq
                    START WITH 1
                    INCREMENT BY 1
                    MINVALUE 0
                    NO MAXVALUE
                    CACHE 1;

                CREATE TABLE alimentos.unidade_faixa_etaria (
                    idfeu integer DEFAULT nextval(\'alimentos.unidade_faixa_etaria_idfeu_seq\'::regclass) NOT NULL,
                    iduni integer NOT NULL,
                    idfae integer NOT NULL,
                    num_inscritos integer NOT NULL,
                    num_matriculados integer NOT NULL
                );
                
                SELECT pg_catalog.setval(\'alimentos.unidade_faixa_etaria_idfeu_seq\', 1, false);
            '
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alimentos.unidade_faixa_etaria');
    }
}
