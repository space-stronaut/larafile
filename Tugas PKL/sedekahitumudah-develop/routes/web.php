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
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\programController;
Route::get('/', 'front\\frontController@index');
Route::get('/testemail', 'front\\frontController@testemail');

// ========== middle =====
    
Route::group(['middleware' => 'auth'], function () {
    Route::get('/laporanperkembangan/create/{id}', 'middle\\programController@createlaporanperkembangan');
    Route::post('/laporanperkembangan/store', 'middle\\programController@storelaporanperkembangan');
    Route::get('/middle', 'middle\\programController@middle');
    Route::get('/detailprogram/{id}', 'middle\\programController@detailprogram')->name('detail');
    Route::resource('program', 'middle\\programController');
    Route::get('/verify/{id}', 'middle\\programController@verify');
    Route::get('/donasi', 'middle\\programController@donasi');
    Route::post('/donasi/konfir/{id}', 'middle\\programController@donasikonfir');
    
    // Proses withdraw (pencairan dana)
    Route::get('withdraw/form/{id}', 'middle\\WithdrawController@form');
    Route::post('withdraw/preview', 'middle\\WithdrawController@preview');
    Route::post('withdraw/create', 'middle\\WithdrawController@create');
    Route::get('withdraw/list', 'middle\\WithdrawController@index');
    Route::get('withdraw/{id}', 'middle\\WithdrawController@view');
});

Auth::routes();
Route::get('/register-partner', 'Auth\\RegisterPartnerController@showRegistrationForm');
Route::post('/register-partner', 'Auth\\RegisterPartnerController@register')->name('register-partner');
Route::get('/partner-thanks', 'Auth\\RegisterPartnerController@thanks');

// ============ front =====
Route::get('/donasi/{id}', 'front\\frontController@donasi');
Route::get('/donasi/{id}/donasi', 'front\\frontController@donasicreate');
Route::post('/donasi/{id}/donasi/store', 'front\\frontController@donasistore');
Route::get('/daftarprogram', 'front\\frontController@daftarprogram');
Route::get('/program/category/{id}', 'front\\frontController@programcategory');
Route::get('/konfirmasi', 'front\\frontController@konfirmasi');
Route::post('/konfirmasi/store/{id}', 'front\\frontController@konfirmasistore');
Route::get('/thx/{id}', 'front\\frontController@thx')->name('thx');
Route::get('/konfirmasi/thx/{id}', 'front\\frontController@thxkonfir')->name('thxkonfir');
Route::post('/report/{id}', 'front\\frontController@report');

// ======= back =====
Route::group(['middleware' => 'App\Http\Middleware\AdminMiddleware'], function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::group(['middleware' => ['auth']], function () {
            Route::get('/dashboard', 'back\\backController@index');

            // Kelola Program
            Route::get('/program', 'back\\ProgramAdminController@index');
            Route::get('/program/{id}/{status}', 'back\\ProgramAdminController@updateStatus');
            Route::get('/published/{id}', 'back\\backController@published');
            Route::get('/selected/{id}', 'back\\backController@selected');            
            Route::get('/detail/{id}', 'back\\backController@detail');
            Route::get('/hapus/{id}', 'back\\backController@hapusProgram');

            // Kelola Kategori
            Route::get('/categories', 'back\\CategoryAdminController@index');
            Route::post('/categories/create', 'back\\CategoryAdminController@create');
            Route::get('/categories/delete/{id}', 'back\\CategoryAdminController@destroy');            
            Route::get('/categories/edit/{id}', 'back\\CategoryAdminController@edit');            
            Route::post('/categories/edit/{id}', 'back\\CategoryAdminController@update');            

            // Kelola Pengguna
            Route::get('/users', 'back\\UserAdminController@index');
            Route::post('/users/create', 'back\\UserAdminController@create');
            Route::get('/users/deleteUser/{id}', 'back\\UserAdminController@delete');
            Route::get('/users/edit/{id}', 'back\\UserAdminController@edit');
            Route::post('/users/{id}/update','back\\UserAdminController@update' );
            Route::get('/users/search','back\\UserAdminController@search');
            Route::get('/users/filter','back\\UserAdminController@filter');
            Route::get('/users/{id}/{status}', 'back\\UserAdminController@updateStatus');

            // Kelola Pencairan Dana Admin
            Route::get('/withdraw', 'back\\WithdrawAdminController@index');
            Route::get('/withdraw/{id}/{status}', 'back\\WithdrawAdminController@updateStatus');
            Route::post('/withdraw/{id}/{status}/alasan', 'back\\WithdrawAdminController@rejectStatus');

            // Setting
            Route::get('/settings', 'back\\backController@globalSetting');
            Route::post('/updateGlobal', 'back\\backController@updateSetting');
        });   
    });
});


