<?php

namespace App\Http\Middleware;

use App\Repository\AuditQueryRepository;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuditQuery
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (!env('DB_SAVE_AUDIT')) {
            return $next($request);
        }

        $retornoDados = $this->validarRequest($request->toArray());
        
        if (!empty($retornoDados)) {
            $repository = new AuditQueryRepository();
            $user = JWTAuth::toUser($request->header('Authorization'));
            if(isset($retornoDados['sem_filtro'])) {
                $path = $request->path();
                $path = substr($path, 4);
                $repository->salvar($retornoDados, $user, $path);
            }else{
                $repository->salvar($retornoDados, $user, $request->segment(2));
            }
        }
        return $next($request);
    }

    public function validarRequest($dados) {
        if (!empty($dados)) {
            $temFiltros = false;
            foreach ($dados as $key => $filtro) {
                if (!empty($filtro)) {
                    $temFiltros = true;
                } else {
                    unset($dados[$key]);
                }
            }
            if ($temFiltros) {
                return $dados;
            }
            return ['sem_filtro' => 'sem_filtro'];
        }
        return ['sem_filtro' => 'sem_filtro'];
    }
}