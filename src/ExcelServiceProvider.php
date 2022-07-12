<?php

namespace XEHub\XePlugin\CustomQuantum\Excel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;

class ExcelServiceProvider extends ServiceProvider
{
    private $package = "excel";
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', $this->package);
    }

    public function register()
    {

    }

}
