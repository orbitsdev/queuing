<?php

namespace App\Providers;

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
            'primary' => '#3B82F6',
            'success' => Color::Green,
            'warning' => Color::Amber,
            'cool-gray' => Color::Zinc,
        ]);

        // Register custom CSS
        
    }
}
