<?php

namespace App\Providers;

use App\Models\ProcessoAdolescente;
use App\Repository\AuditTransactionRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        if (env('DB_SAVE_AUDIT')) {
            Event::listen(['eloquent.updating: *'], function($entidadeClass, $data) {
                $auditTransaction = new AuditTransactionRepository();
                $auditTransaction->salvar('U', $entidadeClass, $data);
            });

            Event::listen(['eloquent.created: *'], function($entidadeClass, $data) {
                $auditTransaction = new AuditTransactionRepository();
                $auditTransaction->salvar('I', $entidadeClass, $data);
            });

            Event::listen(['eloquent.deleted: *'], function($entidadeClass, $data) {
                $auditTransaction = new AuditTransactionRepository();
                $auditTransaction->salvar('D', $entidadeClass, $data);
            });
        }
    }
}
