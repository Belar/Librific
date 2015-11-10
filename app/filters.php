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
	if(Auth::Check()){
		$throttle = Sentry::findThrottlerByUserId(Auth::User()->id);

		if($throttle->isSuspended() || $throttle->isBanned())
			{
				Auth::logout();
				return Redirect::to('/')->with('global_error', 'Depends on violation, your account has been suspended or banned.');
			}
	}
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
	if (Auth::guest()) return Redirect::guest('login');
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});


/*Sentry version*/
Route::filter('authSentry', function()
{
        if (!Sentry::check()) {
		return Redirect::guest('/login');
		}
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
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

/*Fore SSL connection*/

Route::filter('ssl', function()
{
	if(!Request::secure())
	{
		return Redirect::secure(Request::getRequestUri());
	}
});

/*
|--------------------------------------------------------------------------
| Admin/Mod Filter
|--------------------------------------------------------------------------
|
|	Admin/Mod filter for Sentry 2.
|
*/

Route::filter('admin', function()
{
	if (Sentry::check())
	{
		
		if (Sentry::getUser()->hasAccess('admin'))
			{
				// User has access to the given permission
			}
		else
			{
				return Redirect::to('/')->with('global_error', 'This is restricted area. You shall not pass.');
			}	
	}
	else
	{
		return Redirect::guest('login');
	}	
	
});

Route::filter('mod', function()
{
	if (Sentry::check())
	{
		
		if (Sentry::getUser()->hasAccess('mod'))
			{
				// User has access to the given permission
			}
		else
			{
				return Redirect::to('/')->with('global_error', 'This is restricted area. You shall not pass.');
			}	
	}
	else
	{
		return Redirect::guest('login');
	}	
	
});

