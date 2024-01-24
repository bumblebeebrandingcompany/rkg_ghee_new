<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //Admin sidebar for RKG users only.
        Gate::define('show-admin-sidebar', function (User $user) {
            return in_array($user->role, User::rkgAdminRoles(true));
        });

        Gate::define('delete', function (User $user) {
            return ($user->role == 'super_admin');
        });

        Gate::define('rkg_management', function (User $user) {
            return ($user->role == 'super_admin');
        });
        Gate::define('admin_users', function (User $user) {
            return in_array($user->role, ['super_admin', 'admin', 'area_manager']);
        });
        Gate::define('admin_products', function (User $user) {
            return in_array($user->role, ['super_admin', 'admin']);
        });


        //Permissions related to orders
        Gate::define('edit_order', function (User $user) {
            return in_array($user->role, ['order_superviser', 'super_admin', 'admin']);
        });
        Gate::define('cancel_order', function (User $user) {
            return in_array($user->role, ['order_superviser', 'super_admin', 'admin']);
        });
        Gate::define('update_order_status', function (User $user) {
            return in_array($user->role, ['order_manager', 'super_admin', 'admin', 'distributor', 'super_stockist']);
        });
        Gate::define('delete_draft_order', function (User $user) {
            return in_array($user->role, ['super_admin', 'distributor', 'order_manager', 'wholesaler', 'sub_stockist']);
        });

        // Gate::define('reports', function (User $user) {
        //     return in_array($user->role, ['order_manager', 'super_admin', 'admin']);
        // });
    }
}
