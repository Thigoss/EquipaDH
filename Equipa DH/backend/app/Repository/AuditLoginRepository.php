<?php 

namespace App\Repository;

use App\Helpers\EncryptHelper;
use Illuminate\Support\Facades\DB;

class AuditLoginRepository extends BaseRepository
{
    public function salvar($user) {

        if (!env('DB_SAVE_AUDIT')) {
            return;
        }
        
        $dados = [
            'data_hora' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'],
            'user_cpf' => EncryptHelper::encrypt($user->cpf),
            'user_nome' => EncryptHelper::encrypt($user->nome),
            'navegador' => !empty($_SERVER['HTTP_SEC_CH_UA_PLATFORM']) ? $_SERVER['HTTP_SEC_CH_UA_PLATFORM'] : '',
            'sistema_operacional' => !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
            'client_id' => env('AUTH_CLIENT_ID'),
        ];

        DB::connection('auditoria')->table('audit_login')->insert($dados);
    }
}