<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Gates untuk authorization
        Gate::define('manage-users', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-ekstrakurikuler', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-pendaftaran', function (User $user, $ekstrakurikuler = null) {
            if ($user->role === 'admin') {
                return true;
            }

            if ($user->role === 'pembina' && $ekstrakurikuler) {
                return $user->id === $ekstrakurikuler->pembina_id;
            }

            return false;
        });

        Gate::define('view-laporan', function (User $user) {
            return in_array($user->role, ['admin', 'pembina']);
        });

        Gate::define('daftar-ekstrakurikuler', function (User $user) {
            return $user->role === 'siswa' && !$user->sudahTerdaftarEkstrakurikuler();
        });
    }
}
