<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Classe modelo de arquivos de recursos de adesão
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class AdesaoRecursoArquivo extends BaseModel
{
    
    protected $connection = 'pgsql';

    protected $table = 'adesoes_recursos_arquivos';
 
    protected $fillable = [
        'adesao_recurso_id',
        'nome',
        'arquivo'
    ];

    public function recurso()
    {
        return $this->belongsTo(AdesaoRecurso::class, 'adesao_recurso_id', 'id');
    }
}
