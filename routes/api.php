<?php

use App\Http\Middleware\WebMobileApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::middleware([WebMobileApi::class])->group(function () {
    Route::post('login', 'PassportController@login');
    Route::post('register', 'PassportController@register');
});

Route::middleware('auth:api')->group(function () {
    Route::apiResource('dashboard', 'DashboardController');

    Route::apiResource('answer_key', 'AnswerKeyController');
    Route::apiResource('answer_key_info', 'AnswerKeyInfoController');

    Route::apiResource('student_answer_sheet', 'StudentAnswerSheetController');
    Route::apiResource('student_answer_sheet_info', 'StudentAnswerSheetInfoController');
    Route::apiResource('student_answer_sheet_result', 'StudentAnswerSheetResultController');

    Route::apiResource('score_board', 'ScoreBoardController');
    Route::apiResource('support', 'SupportController');
    Route::apiResource('support_conversation', 'SupportConversationController');

    Route::apiResource('student', 'StudentController');
    Route::apiResource('user', 'UserController');

    /** references */
    Route::apiResource('user_role', 'UserRoleController');
    Route::apiResource('subject', 'SubjectController');
    Route::apiResource('section', 'SectionController');
    Route::apiResource('school_year', 'SchoolYearController');
    /** end references */
});

Route::get('test', function () {
    echo 'F.U';
    // dd(str_pad(mt_rand(0, 999999), 8, '0', STR_PAD_LEFT));
    // dd(substr(number_format(time() * rand(), 0, '', ''), 0, 7));
    // dd(random_int(1000000, 9999999));
});