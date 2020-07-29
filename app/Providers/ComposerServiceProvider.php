<?php

namespace App\Providers;

use App\Http\ViewComposers\AdminDashboardComposer;
use App\Http\ViewComposers\EmployeeDashboard;
use App\Http\ViewComposers\EmployeesAssignCourseComposer;
use App\Http\ViewComposers\GroupManagerDashboardComposer;
use App\Http\ViewComposers\ManagerDashboardComposer;
use App\Http\ViewComposers\ShowSlideComposer;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $view = view();
        $view->composer('components.dashboard.admin', AdminDashboardComposer::class);
        $view->composer('components.dashboard.manager', ManagerDashboardComposer::class);
        $view->composer('components.dashboard.group_manager', GroupManagerDashboardComposer::class);
        $view->composer('components.dashboard.employee', EmployeeDashboard::class);
        $view->composer('components.popups.employeesAssignCourse', EmployeesAssignCourseComposer::class);
        $view->composer('components.popups.employeesCompleteCourse', EmployeesAssignCourseComposer::class);
        $view->composer('components.slides.jsonSlide', ShowSlideComposer::class);
        $view->composer('components.slides.htmlSlide', ShowSlideComposer::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
