<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        // 'App\SmallFarmer' => 'App\Policies\SmallFarmerPolicy',
        // 'App\User' => 'App\Policies\UserPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            if ($user->isAdmin()) {
                return true;
            }
        });

        // 사용자
        Gate::define('index-user', function ($user, $nonghyup_id) {
            return $user->nonghyup_id === $nonghyup_id;
        });
        Gate::define('create-users', function ($user, $nonghyup_id) {
            return false;
        });
        Gate::define('edit-users', function ($user, $nonghyup_id) {
            return $user->nonghyup_id === $nonghyup_id;
        });
        Gate::define('show-users', function ($user, $nonghyup_id) {
            return $user->nonghyup_id === $nonghyup_id;
        });
        Gate::define('activate-users', function ($user, $nonghyup_id) {
            return false;
        });
        Gate::define('copy-users', function ($user, $nonghyup_id) {
            return false;
        });
        Gate::define('delete-user', function ($user, $nonghyup_id) {
            return false;
        });


        // 사업비
        Gate::define('index-budgets', function ($user, $nonghyup_id) {
            return $user->nonghyup_id === $nonghyup_id;
        });
        Gate::define('create-budgets', function ($user, $nonghyup_id) {
            return false;
        });
        Gate::define('edit-budgets', function ($user, $budget) {
            return $user->nonghyup_id == $budget->nonghyup_id;
        });
        Gate::define('show-budgets', function ($user, $budget) {
            return $user->nonghyup_id == $budget->$nonghyup_id;
        });
        Gate::define('delete-budgets', function ($user, $budget) {
            return $user->nonghyup_id == $budget->$nonghyup_id;
        });

        // 일손필요농가(소규모·영세농)
        Gate::define('show-small-farmer', function ($user, $farmer) {
            // return ($user->user_id === $farmer->nonghyup_id)
            //       ? Response::allow()
            //       : Response::deny('You must be a super administrator.');
            return $user->nonghyup_id === $farmer->nonghyup_id;
        });
        Gate::define('edit-small-farmer', function ($user, $farmer) {
            return $user->nonghyup_id === $farmer->nonghyup_id;
        });
        Gate::define('delete-small-farmer', function ($user, $farmer) {
            return $user->nonghyup_id === $farmer->nonghyup_id;
        });
        Gate::define('export-small-farmer', function ($user, $nonghyup_id = '') {
            $nonghyup_id = ($nonghyup_id) ? $nonghyup_id : $user->nonghyup_id;
            return $user->nonghyup_id === $nonghyup_id;
        });


        // 일손필요농가(대규모전업농)
        Gate::define('show-large-farmer', function ($user, $farmer) {
            return $user->nonghyup_id === $farmer->nonghyup_id;
        });
        Gate::define('edit-large-farmer', function ($user, $farmer) {
            return $user->nonghyup_id === $farmer->nonghyup_id;
        });
        Gate::define('delete-large-farmer', function ($user, $farmer) {
            return $user->nonghyup_id === $farmer->nonghyup_id;
        });
        Gate::define('export-large-farmer', function ($user, $nonghyup_id = '') {
            $nonghyup_id = ($nonghyup_id) ? $nonghyup_id : $user->nonghyup_id;
            return $user->nonghyup_id === $nonghyup_id;
        });

        // 농기계지원반(소규모영세농)
        Gate::define('show-machine-supporter', function ($user, $supporter) {
            return $user->nonghyup_id === $supporter->nonghyup_id;
        });
        Gate::define('edit-machine-supporter', function ($user, $supporter) {
            return $user->nonghyup_id === $supporter->nonghyup_id;
        });
        Gate::define('delete-machine-supporter', function ($user, $supporter) {
            return $user->nonghyup_id === $supporter->nonghyup_id;
        });
        Gate::define('export-machine-supporter', function ($user, $nonghyup_id = '') {
            $nonghyup_id = ($nonghyup_id) ? $nonghyup_id : $user->nonghyup_id;
            return $user->nonghyup_id === $nonghyup_id;
        });

        // 인력지원반(대규모전업농)
        Gate::define('show-manpower-supporter', function ($user, $supporter) {
            return $user->nonghyup_id === $supporter->nonghyup_id;
        });
        Gate::define('edit-manpower-supporter', function ($user, $supporter) {
            return $user->nonghyup_id === $supporter->nonghyup_id;
        });
        Gate::define('delete-manpower-supporter', function ($user, $supporter) {
            return $user->nonghyup_id === $supporter->nonghyup_id;
        });
        Gate::define('export-manpower-supporter', function ($user, $nonghyup_id = '') {
            $nonghyup_id = ($nonghyup_id) ? $nonghyup_id : $user->nonghyup_id;
            return $user->nonghyup_id === $nonghyup_id;
        });

        // 농작업지원단(교육홍보비) 지출현황
        Gate::define('show-status-education-promotion', function ($user, $supporter) {
            return $user->nonghyup_id === $supporter->nonghyup_id;
        });
        Gate::define('edit-status-education-promotion', function ($user, $supporter) {
            return $user->nonghyup_id === $supporter->nonghyup_id;
        });
        Gate::define('delete-status-education-promotion', function ($user, $supporter) {
            return $user->nonghyup_id === $supporter->nonghyup_id;
        });
        Gate::define('export-status-education-promotion', function ($user, $nonghyup_id = '') {
            $nonghyup_id = ($nonghyup_id) ? $nonghyup_id : $user->nonghyup_id;
            return $user->nonghyup_id === $nonghyup_id;
        });

        // 농기계지원반 지원현황
        Gate::define('show-status-machine-supporter', function ($user, $supporter) {
            return $user->nonghyup_id === $supporter->nonghyup_id;
        });
        Gate::define('edit-status-machine-supporter', function ($user, $supporter) {
            return $user->nonghyup_id === $supporter->nonghyup_id;
        });
        Gate::define('delete-status-machine-supporter', function ($user, $supporter) {
            return $user->nonghyup_id === $supporter->nonghyup_id;
        });
        Gate::define('export-status-machine-supporter', function ($user, $nonghyup_id = '') {
            $nonghyup_id = ($nonghyup_id) ? $nonghyup_id : $user->nonghyup_id;
            return $user->nonghyup_id === $nonghyup_id;
        });

        // 인력지원반 지원현황
        Gate::define('show-status-manpower-supporter', function ($user, $supporter) {
            return $user->nonghyup_id === $supporter->nonghyup_id;
        });
        Gate::define('edit-status-manpower-supporter', function ($user, $supporter) {
            return $user->nonghyup_id === $supporter->nonghyup_id;
        });
        Gate::define('delete-status-manpower-supporter', function ($user, $supporter) {
            return $user->nonghyup_id === $supporter->nonghyup_id;
        });
        Gate::define('export-status-manpower-supporter', function ($user, $nonghyup_id = '') {
            $nonghyup_id = ($nonghyup_id) ? $nonghyup_id : $user->nonghyup_id;
            return $user->nonghyup_id === $nonghyup_id;
        });

        // 센터운영비(인건비) 지급현황
        Gate::define('show-status-labor-payment', function ($user, $row) {
            return $user->nonghyup_id === $row->nonghyup_id;
        });
        Gate::define('edit-status-labor-payment', function ($user, $row) {
            return $user->nonghyup_id === $row->nonghyup_id;
        });
        Gate::define('delete-status-labor-payment', function ($user, $row) {
            return $user->nonghyup_id === $row->nonghyup_id;
        });
        Gate::define('export-status-labor-payment', function ($user, $nonghyup_id = '') {
            $nonghyup_id = ($nonghyup_id) ? $nonghyup_id : $user->nonghyup_id;
            return $user->nonghyup_id === $nonghyup_id;
        });

        // 센터운영비(인건비) 지급현황
        Gate::define('show-status-operating-cost', function ($user, $row) {
            return $user->nonghyup_id === $row->nonghyup_id;
        });
        Gate::define('edit-status-operating-cost', function ($user, $row) {
            return $user->nonghyup_id === $row->nonghyup_id;
        });
        Gate::define('delete-status-operating-cost', function ($user, $row) {
            return $user->nonghyup_id === $row->nonghyup_id;
        });
        Gate::define('export-status-operating-cost', function ($user, $nonghyup_id = '') {
            $nonghyup_id = ($nonghyup_id) ? $nonghyup_id : $user->nonghyup_id;
            return $user->nonghyup_id === $nonghyup_id;
        });

        // 데이터 입력 스케쥴
        Gate::define('show-schedule', function ($user, $schedule) {
            return false;
        });
        Gate::define('edit-schedule', function ($user, $schedule) {
            return false;
        });
    }
}
