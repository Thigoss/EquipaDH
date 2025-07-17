<?php

namespace App\Models;

class CronogramaArquivoClassificacaoMunicipal extends BaseModel
{

    public $table = 'cronogramas_arquivos_classificacao_municipal';

    public $save_audit_transaction = true;

    protected $fillable = [
        'cronograma_id',
        'nome',
        'arquivo',
        'ativo',
    ];

    public function cronograma()
    {
        return $this->belongsTo(Cronograma::class);
    }
}