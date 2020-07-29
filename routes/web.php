<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;

Auth::routes(['verify' => true]);
Route::get('signup/success', 'Auth\RegisterController@signupSuccess')->name('signup.success');

Route::get('/home', 'HomeController@index')->name('home');
Route::redirect('/', url('/home'))->middleware('auth');

Route::get('re-login', function() {
    if(Auth::user()) {
        Auth::logout();
    }

    return redirect(route('login'));

})->name('re-login');
Route::post('/imageUpload', 'UploadController@index');
Route::group(['prefix' => 'admin', 'middleware' => 'role:admin', 'namespace' => 'Admin'], function () {
    Route::resource('users', 'UserController')->except(['index', 'show']);
    Route::post('users/bulk-delete', 'UserController@bulkDelete')->name('users.bulk-delete');

    Route::resource('groups', 'GroupController')->except(['index', 'show']);
    Route::post('groups/bulk-delete', 'GroupController@bulkDelete')->name('groups.bulk-delete');
    Route::resource('groups.managers', 'GroupManagerController')->except(['index', 'show']);

    Route::resource('organizations', 'OrganizationController')->except(['index', 'show']);
    Route::post('organizations/bulk-delete', 'OrganizationController@bulkDelete')->name('organizations.bulk-delete');

    Route::resource('courses', 'CourseController')->except(['index', 'show']);
    Route::post('courses/bulk-delete', 'CourseController@bulkDelete')->name('courses.bulk-delete');

    Route::resource('lessons', 'LessonController')->except('index', 'show');
    Route::post('lessons/bulk-delete', 'LessonController@bulkDelete')->name('lessons.bulk-delete');
    Route::post('lessons/sort-slides', 'LessonController@sortSlides')->name('lessons.sortSlides');
    Route::post('course/sort-lessons', 'LessonController@sortLessons')->name('course.sortLessons');

    Route::resource('slides', 'SlideController')->except('index', 'show');
    Route::post('slides/bulk-delete', 'SlideController@bulkDelete')->name('slides.bulk-delete');
    Route::get('slides/{slide}/clone', 'SlideController@copy')->name('slides.clone');
});

Route::group(['middleware' => 'role:admin,manager', 'namespace' => 'Manager'], function () {
    Route::resource('policies', 'PolicyController')->except(['show']);
    Route::get('policies/{policy}/stats', 'PolicyController@stats')->name('policies.stats');
    Route::get('courses/create', 'CourseController@create')->name('courses.createExternal');
    Route::post('courses', 'CourseController@store')->name('courses.storeExternal');
    Route::delete('courses/{course}', 'CourseController@destroy')->name('courses.destroyExternal');
    Route::get('courses/{course}/edit', 'CourseController@edit')->name('courses.editExternal');
    Route::put('courses/{course}/update', 'CourseController@update')->name('courses.updateExternal');

    Route::group(['prefix' => 'employees'], function() {
        Route::post('import', 'EmployeeController@import')->name('employees.import');
        Route::post('bulkArchive', 'EmployeeController@bulkArchive')->name('employees.bulkArchive');
        Route::post('bulkRestore', 'EmployeeController@bulkRestore')->name('employees.bulkRestore');
        Route::post('bulk-mark-completed', 'EmployeeController@bulkMarkCompleted')->name('employees.bulkMarkCompleted');
        Route::post('bulkDelete', 'EmployeeController@bulkDelete')->name('employees.bulkDelete');
        Route::get('archive', 'EmployeeController@viewArchive')->name('employees.viewArchive');
        Route::post('bulk-assign', 'EmployeeController@bulkAssign')->name('employees.bulkAssign');
    });
    Route::resource('employees', 'EmployeeController')->except('index');

    Route::group(['prefix' => 'reports'], function() {
        Route::get('organization', 'ReportsController@index')->name('reports.organization');
    });

    Route::group(['prefix' => 'courses'], function() {
        Route::get('{course}/assign', 'CourseController@assign')->name('courses.assign');
        Route::post('{course}/assign', 'CourseController@storeAssignments')->name('courses.storeAssignments');
        Route::get('{course}/certificate/{employee}', 'CourseController@showEmployeeCertificate')->name('courses.showEmployeeCertificate');
        Route::get('{course}/settings', 'CourseController@settings')->name('courses.settings');
        Route::post('{course}/settings', 'CourseController@storeSettings')->name('courses.storeSettings');
    });

    Route::post('job-role/{jobRole}/assign-courses', 'JobRoleController@assignCourses')->name('job-role.assignCourses');
});

Route::group(['middleware' => 'auth', 'namespace' => 'Employee'], function() {
    Route::group(['prefix' => 'course'], function() {
        Route::get('{course}', 'CourseController@show')->name('course.show');
        Route::get('{course}/resume', 'CourseController@resume')->name('course.resume');
        Route::post('{course}/complete', 'CourseController@markComplete')->name('course.markCompleteExternal');
        Route::get('{course}/retake', 'CourseController@retake')->name('course.retake');
        Route::get('{course}/finish', 'CourseController@finish')->name('course.finish');
        Route::get('{course}/results', 'CourseController@showResults')->name('course.showResults');
        Route::get('{course}/certificate', 'CourseController@showCertificate')->name('course.certificate');
        Route::get('{course}/manager', 'CourseController@managerView')->name('course.managerView');
    });

    Route::group(['prefix' => 'lessons'], function() {
        Route::get('{lesson}', 'LessonController@show')->name('lesson.show');
        Route::get('{lesson}/save-results', 'LessonController@storeResults')->name('lesson.saveResults');
    });

    Route::group(['prefix' => 'slides'], function() {
        Route::get('{slide}', 'SlideController@show')->name('slide.show');
        Route::post('{slide}', 'SlideController@storeAnswer')->name('slide.storeAnswer');
    });

    Route::get('policies/{policy}', 'PolicyController@show')->name('policy.show');
    Route::get('policies/{policy}/read', 'PolicyController@read')->name('policy.read');
});

Route::get('assets/{course}/slides/{slide}/{attachment}', 'Employee\SlideController@attachment')->name('slide.attachment');

Route::group(['middleware' => 'role:admin,group_manager', 'namespace' => 'GroupManager'], function () {
    Route::resource('reports', 'ReportController');
});
