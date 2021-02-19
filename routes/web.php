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

$this->group(['middleware' => ['auth'], 'namespace' => 'Admin', 'prefix' => 'admin'], function(){
    $this->any('history-search', 'BalanceController@searchHistory')->name('history.search');
    $this->get('history', 'BalanceController@history')->name('admin.history');
    
    $this->post('transfer', 'BalanceController@transferStore')->name('transfer.store'); 
    $this->post('confirm-transfer', 'BalanceController@confirmTransfer')->name('confirm.transfer');
    $this->get('transfer', 'BalanceController@transfer')->name('balance.transfer');

    $this->post('withdraw', 'BalanceController@withdrawStore')->name('withdraw.store');
    $this->get('withdraw', 'BalanceController@withdraw')->name('balance.withdraw');

    $this->post('deposit', 'BalanceController@depositStore')->name('deposit.store');
    $this->get('deposit', 'BalanceController@deposit')->name('balance.deposit');

    $this->get('balance', 'BalanceController@index')->name('admin.balance');
    $this->get('/', 'AdminController@index')->name('admin.home');
});

$this->get('meu-perfil', 'Admin\UserController@profile')->name('profile')->middleware('auth');
$this->post('atualizar-perfil', 'Admin\UserController@profileUpdate')->name('profile.update')->middleware('auth');

$this->get('/', 'SiteController@index')->name('home.index');


Auth::routes();

