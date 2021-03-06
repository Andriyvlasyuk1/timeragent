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

Route::get('/', function () {
    if (!auth()->check() && !app()->environment() === 'local') {
        return redirect()->to(config('app.promo_url'));
    }
    return view('welcome');
})->middleware('isVerified');

Auth::routes();

// Route::domain('app.time-tracker-laravel.dev')->group(function() {
        // Route::get('/', 'HomeController@index')->name('home');
// });

Route::get('/', 'HomeController@index')->name('home')->middleware(['isVerified','auth']);

Route::get('organization/{organization}/accept', 'Organization\OrganizationController@acceptInvite')
    ->name('organization.accept_invite')
    ->middleware('auth');

Route::get('teams/{team}/{user}/accept/{token}', 'TeamController@acceptInvite')
    ->name('teams.accept_invite')
    ->middleware('auth');
Route::get('resend-verify-email', function() {
    return view('auth.verifying.email');
})->name('verify.email');
Route::post('resend-verify-email', 'Auth\RegisterController@resendVerifyEmail')->name('verify.email');
Route::get('verification-sent', function() {
    return view('auth.verifying.verification-sent');
})->name('verify.sent');
/**
 * Teamwork routes
 */
Route::group(['prefix' => 'teams', 'namespace' => 'Teamwork'], function()
{
    Route::get('/', 'TeamController@index')->name('teams.index');
    Route::get('create', 'TeamController@create')->name('teams.create');
    Route::post('teams', 'TeamController@store')->name('teams.store');
    Route::get('edit/{id}', 'TeamController@edit')->name('teams.edit');
    Route::put('edit/{id}', 'TeamController@update')->name('teams.update');
    Route::delete('destroy/{id}', 'TeamController@destroy')->name('teams.destroy');
    Route::get('switch/{id}', 'TeamController@switchTeam')->name('teams.switch');

    Route::get('members/{id}', 'TeamMemberController@show')->name('teams.members.show');
    Route::get('members/resend/{invite_id}', 'TeamMemberController@resendInvite')->name('teams.members.resend_invite');
    Route::post('members/{id}', 'TeamMemberController@invite')->name('teams.members.invite');
    Route::delete('members/{id}/{user_id}', 'TeamMemberController@destroy')->name('teams.members.destroy');

//    Route::get('accept/{token}', 'AuthController@acceptInvite')->name('teams.accept_invite')->middleware('auth');
});

Route::get('/{vue_capture?}', function () {
    return view('tracker');
})->where('vue_capture', '[\/\w\.-]*')->middleware(['isVerified','auth']);
