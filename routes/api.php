<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['api'])->group(function () {

Route::any('exam/search', 'Exam\ExamController@search');
Route::any('exam/add', 'Exam\ExamController@add');
Route::any('exam/edit', 'Exam\ExamController@edit');
Route::any('exam/editAdmin', 'Exam\ExamController@editAdmin');
Route::any('exam/login', 'Exam\ExamController@login');
Route::any('exam/sms', 'Exam\ExamController@sms');

//获取学校 + 班级数据
Route::any('school/search', 'Exam\SchoolController@search');
Route::any('school/add', 'Exam\SchoolController@add');
});
