<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (!Session::has("user_id")) {
        return Redirect::to("/login");
    }
});

Route::filter("admin", function()
{
    if (User::all()->count() > 0) {
        $user = User::find(Session::get("user_id"));

        if ($user->admin == 0) {
            return Redirect::to("/")->withErrors(['Access Denied']);
        }
    }
});

Route::filter("cardsort", function()
{
    $user = User::find(Session::get("user_id"));

    if (User::all()->count() > 0 && $user->cardsort == 0 && $user->admin == 0) {
        return Redirect::to("/")->withErrors(['Access Denied']);
    }
});

Route::filter("fishshark", function()
{
    $user = User::find(Session::get("user_id"));

    if (User::all()->count() > 0 && $user->fishshark == 0 && $user->admin == 0) {
        return Redirect::to("/")->withErrors(['Access Denied']);
    }
});

Route::filter("vocab", function()
{
    $user = User::find(Session::get("user_id"));

    if (User::all()->count() > 0 && $user->vocab == 0 && $user->admin == 0) {
        return Redirect::to("/")->withErrors(['Access Denied']);
    }
});

Route::filter("questionnaire", function()
{
    $user = User::find(Session::get("user_id"));

    if (User::all()->count() > 0 && $user->questionnaire == 0 && $user->admin == 0) {
        return Redirect::to("/")->withErrors(['Access Denied']);
    }
});

Route::filter("mrant", function()
{
    $user = User::find(Session::get("user_id"));

    if (User::all()->count() > 0 && $user->mrant == 0 && $user->admin == 0) {
        return Redirect::to("/")->withErrors(['Access Denied']);
    }
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
