<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Use Bootstrap 5 pagination views
        Paginator::useBootstrapFive();

        // Prevents connections from blocking indefinitely when waiting for a row lock
        DB::statement('SET SESSION innodb_lock_wait_timeout = 5');
    }
}
