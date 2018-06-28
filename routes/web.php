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

Route::get('/', 'IndexController@entry')->name('login');

Route::get('/vk-auth', 'VkAuthController@vkGetAuthCode');
Route::get('/vk-redirect', 'VkAuthController@vkRedirect');

Route::get('/logout', 'Auth\LoginController@appLogout');

Route::middleware('auth')->group(function(){
    Route::get('/accounts', 'AccountsController@list');
    Route::get('/accounts/{accountId}', 'CampaignsController@list');
    Route::get('/accounts/{accountId}/{campaignId}', 'AdsController@list');
    Route::post('/save-ad/{id}', 'AdsController@save');
    Route::post('/delete-ad/{id}', 'AdsController@delete');
});
