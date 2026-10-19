<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;


Route::controller(AuthenticationController::class)->group(function () {
    Route::get('/',"login")->name('web.login');
    Route::post('/login',"authenticate")->name('login');

});

Route::controller(HomeController::class)->group(function () {
    Route::get('/dashboard',"index")->name('home');
});


Route::controller(MemberController::class)->prefix('/members')->group(function () {
   Route::get('/create',"createMember")->name('member.create');
   Route::post('/store',"storeMember")->name('members.store');
   Route::get('/import','import')->name('members.import');
   Route::post('/import','importMembers')->name('members.import.store');
   Route::get('/','index')->name('members.index');
   Route::get('/edit/{member}','edit')->name('member.edit');
});
