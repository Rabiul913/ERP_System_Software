<?php

namespace App\Providers;

use ReflectionClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        // Register the HasComId trait as a global scope for all models
        // foreach (glob(app_path('Modules/*/Models/*.php')) as $file) {
        //     $modelClass = 'App\\' . str_replace('/', '\\', substr(dirname($file), strlen(app_path()) + 1)) . '\\' . basename($file, '.php');
        //     if (is_subclass_of($modelClass, 'Illuminate\\Database\\Eloquent\\Model')) {
        //         $modelClass::addGlobalScope(new \App\Traits\CreateComId);
        //     }
        // }
        
        if (is_subclass_of((new ReflectionClass(Model::class)), 'Illuminate\\Database\\Eloquent\\Model')) {
            (new ReflectionClass(Model::class))::addGlobalScope(new \App\Traits\CreateComId);
        }

        Blade::directive('money', function ($money) {
            return "<?php echo number_format($money, 2, '.', ','); ?>";
        });


    }
}
