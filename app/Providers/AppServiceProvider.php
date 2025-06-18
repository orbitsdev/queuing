<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Branch;
use App\Observers\BranchObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Colors\Color;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->configureFilament();
        $this->configGates();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();


    }


    public function configureFilament(){
        FilamentColor::register([
            //indigo
            'indigo' => Color::Indigo,
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::Blue,
            'primary' => '#007dfe',
            'success' => Color::Green,
            'warning' => Color::Amber,
            'cool-gray' => Color::Zinc,
            'dark-gray' => '#3B3B3B',
        ]);

        // Register custom CSS

    }

    public function configGates(){
        //superadmin
        Gate::define('superadmin', function (User $user) {
            return $user->role === 'superadmin';
        });

        //super admin or admin
        Gate::define('superadmin_or_admin', function (User $user) {
            return $user->role === 'superadmin' || $user->role === 'admin';
        });

        //admin
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });

        //staff
        Gate::define('staff', function (User $user) {
            return $user->role === 'staff';
        });


    }
}
