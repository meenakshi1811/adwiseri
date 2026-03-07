<?php
namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\View\Composers\SettingViewComposer;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {

        // Attach the composer to multiple views using wildcards
        View::composer(['web.layout.footer','admin.*'], SettingViewComposer::class);

        // Or, attach to all views
        // View::composer('*', SettingViewComposer::class);
    }

    public function register()
    {
        //
    }
}
