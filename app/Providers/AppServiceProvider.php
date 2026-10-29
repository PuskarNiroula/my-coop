<?php

namespace App\Providers;

use App\Livewire\MembersTable;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Livewire::component('members-table', MembersTable::class);
    }
}
