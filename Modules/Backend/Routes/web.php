<?php

use Illuminate\Support\Facades\Route;


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

Route::prefix('admin')->group(function () {
    //Department
    Route::get('/department', 'DepartmentController@department')->name('admin.department');
    Route::post('/department/store', 'DepartmentController@create')->name('department.store');
    Route::get('/department/delete/{id}', 'DepartmentController@delete')->name('department.delete');
    Route::get('/department/edit/{id}', 'DepartmentController@edit')->name('department.edit');
    Route::put('/department/update/{id}', 'DepartmentController@update')->name('department.update');

    //Doctor
    Route::get('/doctor', 'DoctorController@doctor')->name('admin.doctor');
    Route::post('/doctor/store', 'DoctorController@create')->name('doctor.store');
    Route::get('/doctor/delete/{id}', 'DoctorController@delete')->name('doctor.delete');
    Route::get('/doctor/edit/{id}', 'DoctorController@edit')->name('doctor.edit');
    Route::put('/doctor/update/{id}', 'DoctorController@update')->name('doctor.update');
    Route::get('/doctor/status/{id}', 'DoctorController@changestatus')->name('doctor.status');
});
