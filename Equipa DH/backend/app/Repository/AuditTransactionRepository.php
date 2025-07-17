<?php 

namespace App\Repository;

use App\Helpers\EncryptHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuditTransactionRepository extends BaseRepository {

    public function salvar($tipo, $entidadeClass, $data) {
        $formatModelPath = explode('App', $entidadeClass);
        $formatModelPath = str_replace('\\\\', '\\', $formatModelPath[1]);
        $formatModelPath = 'App'.$formatModelPath;
        $model = app($formatModelPath);
        
        if (isset($model->save_audit_transaction)) {
            if ($model->save_audit_transaction) {
                $authorization = request()->header('Authorization');
                $token = str_replace(['Bearer', ' '], ['', ''], $authorization);

                if ($token != 'undefined') {
                    $user = auth()->user();

                    $dadosSalvar = $data[0]->toArray();
                    if (isset($model->encryptedAttributes)) {
                        foreach($model->encryptedAttributes as $attribute) {
                            if (isset($dadosSalvar[$attribute])) {
                                $dadosSalvar[$attribute] = EncryptHelper::encrypt($dadosSalvar[$attribute]);
                            }
                        }
                    }

                    $insert = [
                        'user_nome' => $user ? EncryptHelper::encrypt($user->nome) : EncryptHelper::encrypt('Usuário não autenticado.'),
                        'user_cpf' => $user ? EncryptHelper::encrypt($user->cpf) : EncryptHelper::encrypt('Usuário não autenticado.'),
                        'entidade' => $model->table,
                        'dados' => json_encode($dadosSalvar),
                        'referencia_id' => $dadosSalvar['id'],
                        'data_hora' => date('Y-m-d H:i:s'),
                        'ip' => $_SERVER['REMOTE_ADDR'],
                        'tipo' => $tipo,
                        'navegador' => !empty($_SERVER['HTTP_SEC_CH_UA_PLATFORM']) ? $_SERVER['HTTP_SEC_CH_UA_PLATFORM'] : '',
                        'sistema_operacional' => !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
                        'client_id' => env('AUTH_CLIENT_ID'),
                    ];
                    DB::connection('auditoria')->table('audit_transaction')->insert($insert);
                }
            }
        }
    }
}