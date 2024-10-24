<?php

namespace App\Providers;

use App\Models\Admin;
use App\Policies\AdminPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @var array
     * 策略映射
     */
    protected array $policies = [
        Admin::class => AdminPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
