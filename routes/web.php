<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SavingController;
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




Route::controller(SavingController::class)->prefix('/savings')->group(function () {
    Route::get('/','index')->name('savings.index');
    Route::get('/accounts','savingAccounts')->name('savings.accounts');
    Route::get('/accounts/create','create')->name('savings.create');
    Route::post('/accounts/store','store')->name('savings.store');
    Route::get('/accounts/import','import')->name('savings.import');
    Route::post('/accounts/import','importBulk')->name('saving-accounts.import');;
    Route::get('/saving/accounts/import-template', "downloadTemplate")->name('saving-accounts.import-template');
    Route::get('/deposit/create','deposit')->name('savings.deposit.create');
    Route::post('/deposit/store','deposit')->name('savings.deposit.store');
    Route::get('/withdrawals/create','withdraw')->name('savings.withdrawal.create');
    Route::post('/withdrawals/store','withdraw')->name('savings.withdrawal.store');

});
});
