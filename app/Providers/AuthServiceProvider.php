<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        $permissions = [
            'quan-ly-kho',
            'quan-ly-san-pham',
            'quan-ly-danh-gia',
            'quan-ly-tai-khoan',
            'quan-ly-van-chuyen-khuyen-mai',
            'quan-ly-hoa-don',
            'quan-ly-bai-viet',
            'quan-ly-quyen'
        ];

        foreach ($permissions as $permission) {
            Gate::define($permission, function ($user) use ($permission) {
                return $user->staff && $user->staff->hasPermission($permission);
            });
        }
    }
}
