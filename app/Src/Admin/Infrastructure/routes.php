<?php

declare(strict_types=1);

use App\Src\Admin\Modules\Auth\ForgotPassword;
use App\Src\Admin\Modules\Auth\Login;
use App\Src\Admin\Modules\Auth\ResetPassword;
use App\Src\Admin\Modules\Company\CompanyTable;
use App\Src\Admin\Modules\Profile\ChangePassword;
use App\Src\Admin\Modules\Profile\Dashboard;
use App\Src\Admin\Modules\Profile\Logout;
use App\Src\Admin\Modules\Profile\Profile;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes For Admin Panel
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/', Login::class)->name('index')->middleware(['guest:moderator']);
    Route::group(['as' => 'admin.', 'prefix' => 'admin'], function () {
        Route::group(['as' => 'auth.', 'middleware' => ['guest:moderator']], function () {
            Route::get('login', Login::class)->name('login');
            Route::get('forgot-password', ForgotPassword::class)->name('forgot-password');
            Route::get('reset-password/{token}', ResetPassword::class)->name('reset-password');
        });
        Route::group(['middleware' => ['auth:moderator', 'moderator.active']], function () {
            Route::get('/', Dashboard::class)->name('index');
            Route::get('dashboard', Dashboard::class)->name('dashboard');

            // Profile
            Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
                Route::get('/', Profile::class)->name('index');
                Route::get('change-password', ChangePassword::class)->name('change-password');
            });

            // Company
            Route::group(['prefix' => 'company', 'as' => 'company.'], function () {
                Route::get('/', CompanyTable::class)->name('index');
                Route::get('create', App\Src\Admin\Modules\Company\CreateCompany::class)->name('create');
                Route::get('edit/{company}', App\Src\Admin\Modules\Company\EditCompany::class)->name('edit');
                Route::get('details/{company}', App\Src\Admin\Modules\Company\CompanyDetails::class)->name('details');
            });
        });
    });
});
