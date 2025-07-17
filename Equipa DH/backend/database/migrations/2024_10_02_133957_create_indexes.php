<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Adesoes table indexes
        DB::statement('CREATE INDEX IF NOT EXISTS idx_user_id ON adesoes (user_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_instituicao_id ON adesoes (instituicao_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_cronograma_id ON adesoes (cronograma_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_situacao ON adesoes (situacao)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_tipo ON adesoes (tipo)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_created_at ON adesoes (created_at)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_updated_at ON adesoes (updated_at)');

        // Solicitacoes table indexes
        DB::statement('CREATE INDEX IF NOT EXISTS idx_user_id ON solicitacoes (user_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_instituicao_id ON solicitacoes (instituicao_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_sexo_id ON solicitacoes (sexo_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_orientacao_sexual_id ON solicitacoes (orientacao_sexual_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_raca_id ON solicitacoes (raca_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_escolaridade_id ON solicitacoes (escolaridade_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_deficiencia_id ON solicitacoes (deficiencia_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_situacao ON solicitacoes (situacao)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_created_at ON solicitacoes (created_at)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_updated_at ON solicitacoes (updated_at)');

        // Unidades table indexes
        DB::statement('CREATE INDEX IF NOT EXISTS idx_nome ON unidades (nome)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_estado_id ON unidades (estado_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_cidade_id ON unidades (cidade_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_ativo ON unidades (ativo)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_updated_at ON unidades (updated_at)');

        // Instituicoes table indexes
        DB::statement('CREATE INDEX IF NOT EXISTS idx_razao_social ON instituicoes (razao_social)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_cnpj ON instituicoes (cnpj)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_estado_id ON instituicoes (estado_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_cidade_id ON instituicoes (cidade_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_ativo ON instituicoes (ativo)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Adesoes table indexes
        Schema::table('adesoes', function (Blueprint $table) {
            $table->dropIndex('idx_user_id');
            $table->dropIndex('idx_instituicao_id');
            $table->dropIndex('idx_cronograma_id');
            $table->dropIndex('idx_situacao');
            $table->dropIndex('idx_tipo');
            $table->dropIndex('idx_created_at');
            $table->dropIndex('idx_updated_at');
        });

        // Solicitacoes table indexes
        Schema::table('solicitacoes', function (Blueprint $table) {
            $table->dropIndex('idx_user_id');
            $table->dropIndex('idx_instituicao_id');
            $table->dropIndex('idx_sexo_id');
            $table->dropIndex('idx_orientacao_sexual_id');
            $table->dropIndex('idx_raca_id');
            $table->dropIndex('idx_escolaridade_id');
            $table->dropIndex('idx_deficiencia_id');
            $table->dropIndex('idx_situacao');
            $table->dropIndex('idx_created_at');
            $table->dropIndex('idx_updated_at');
        });

        // Unidades table indexes
        Schema::table('unidades', function (Blueprint $table) {
            $table->dropIndex('idx_nome');
            $table->dropIndex('idx_estado_id');
            $table->dropIndex('idx_cidade_id');
            $table->dropIndex('idx_ativo');
            $table->dropIndex('idx_updated_at');
        });

        // Instituicoes table indexes
        Schema::table('instituicoes', function (Blueprint $table) {
            $table->dropIndex('idx_razao_social');
            $table->dropIndex('idx_cnpj');
            $table->dropIndex('idx_estado_id');
            $table->dropIndex('idx_cidade_id');
            $table->dropIndex('idx_ativo');
        });
    }
}