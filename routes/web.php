<?php

use App\Http\Controllers\AccountSetupController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DayBookController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\FiscalYearSetupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SavingController;
use App\Http\Controllers\YearSetupController;
use Illuminate\Support\Facades\Route;


Route::controller(AuthenticationController::class)->group(function () {
    Route::get('/',"login")->name('web.login');
    Route::get('/login',"login")->name('login');

    Route::post('/login',"authenticate")->name('login');

});


Route::middleware(['auth'])->group(function () {


    Route::controller(HomeController::class)->group(function () {
        Route::get('/dashboard', "index")->name('home');
    });


    Route::controller(MemberController::class)->prefix('/members')->group(function () {
        Route::get('/create', "createMember")->name('member.create');
        Route::post('/store', "storeMember")->name('members.store');
        Route::get('/import', 'import')->name('members.import');
        Route::post('/import', 'importMembers')->name('members.import.store');
        Route::get('/', 'index')->name('members.index');
        Route::get('/edit/{member}', 'edit')->name('member.edit');
    });

    Route::controller(SavingController::class)->prefix('/savings')->group(function () {Route::get('/','index')->name('savings.index');

        Route::get('/accounts','savingAccounts')->name('savings.accounts');
        Route::get('/accounts/create','create')->name('savings.create');
        Route::post('/accounts/store','storeSaving')->name('savings.store');
        Route::get('/accounts/import','import')->name('savings.import');
        Route::post('/accounts/import','importBulk')->name('saving-accounts.import');;
        Route::get('/saving/accounts/import-template', "downloadTemplate")->name('saving-accounts.import-template');

    });


    Route::controller(DayBookController::class)->prefix('/day-books')->group(function () {
        Route::get('/','index')->name('daybook.index');
        Route::get('/create','create')->name('daybook.create');
        Route::post('/store','store')->name('daybook.store');
        Route::get('/{dayBook}/close','close')->name('daybook.close');
    });

    Route::controller(AccountSetupController::class)->prefix('/account-setup')->group(function () {
        Route::get('/','index')->name('account-types.index');
        Route::get('/create','create')->name('account-types.create');
        Route::post('/store','store')->name('account-types.store');
        Route::patch('{accountTypeId}/activate','activate')->name('account-types.activate');
        Route::patch('/{accountTypeId}/deactivate','deactivate')->name('account-types.deactivate');
        Route::get('/{accountType}/edit','edit')->name('account-types.edit');
        Route::post('/{accountType}/update','update')->name('account-types.update');

    });


    Route::controller(FiscalYearSetupController::class)->prefix('/fiscal-year-setup')->group(function () {
        Route::get('/','index')->name('fiscal-year.index');
        Route::get('/create','create')->name('fiscal-year.create');
        Route::post('/store','store')->name('fiscal-year.store');
        Route::patch('{yearId}/activate','activate')->name('fiscal-year.activate');
        Route::patch('{yearId}/deactivate}','deactivate')->name('fiscal-year.deactivate');
        Route::patch('/{yearId}/mark-as-current','markCurrent')->name('fiscal-year.mark-current');
        Route::patch('/{yearId}/close','close')->name('fiscal-year.close');
    });

    Route::controller(YearSetupController::class)->prefix('/year-setup')->group(function () {
        Route::get('/','index')->name('year.index');
        Route::get('/create','create')->name('year.create');
        Route::post('/store','store')->name('year.store');
        Route::patch('{yearId}/activate','activate')->name('year.activate');
        Route::patch('{yearId}/deactivate','deactivate')->name('year.deactivate');
        Route::get('/{year}/edit','edit')->name('year.edit');
        Route::put('/{year}/update','update')->name('year.update');
        Route::patch('/{yearId}/mark-as-current','markAsCurrent')->name('year.mark-current');
    });

    Route::controller(DepositController::class)->prefix('/deposits')->group(function () {
        Route::get('/create','index')->name('savings.deposit.create');
        Route::post('/store','makeDeposit')->name('savings.deposit.store');
    });

});
