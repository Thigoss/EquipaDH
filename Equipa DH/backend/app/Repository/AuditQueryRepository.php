<?php 

namespace App\Repository;

use App\Helpers\EncryptHelper;
use Illuminate\Support\Facades\DB;

class AuditQueryRepository extends BaseRepository
{
    public function salvar($request, $user, $entidade) {

        $dados = [
            'user_cpf' => EncryptHelper::encrypt($user->cpf),
            'user_nome' => EncryptHelper::encrypt($user->nome),
            'entidade' => $entidade,
            'filtros' => json_encode($request),
            'data_hora' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'],
            'navegador' => !empty($_SERVER['HTTP_SEC_CH_UA_PLATFORM']) ? $_SERVER['HTTP_SEC_CH_UA_PLATFORM'] : '',
            'sistema_operacional' => !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
            'client_id' => env('AUTH_CLIENT_ID'),
        ];

        DB::connection('auditoria')->table('audit_query')->insert($dados);
    }
}