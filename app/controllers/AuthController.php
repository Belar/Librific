<?php

class AuthController extends \BaseController {

	public function login()
	{
		if ( ! Sentry::check())
			{
				return View::make('auth.login', array('pageTitle' => 'Login'));
			}
			else
			{
				return Redirect::to('/')->with('global_error', 'You are already logged in.');
			}
	}

	public function loginTry()
	{
		try
			{
				Input::only('email', 'password','remember_me');
				// Set login credentials
				$credentials = array(
					'email'    => Input::get('email'),
					'password' => Input::get('password'),
				);

				// Try to authenticate the user
				if(Input::get('remember_me') == true){
					$user = Sentry::authenticateAndRemember($credentials);
				}
				else{
					$user = Sentry::authenticate($credentials);
				}

				return Redirect::intended('/')->with('global_success', 'You are now logged in.');
			}
			catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
			{
				return Redirect::to('/login')->with('login_error', 'Login field is required.');
			}
			catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
			{
				return Redirect::to('/login')->with('login_error', 'Password field is required.');
			}
			catch (Cartalyst\Sentry\Users\WrongPasswordException $e)
			{
				return Redirect::to('/login')->with('login_error', 'Your username/password combination was incorrect.');
			}
			catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
			{
				return Redirect::to('/login')->with('login_error', 'Your username/password combination was incorrect.');
			}
			catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
			{
				return Redirect::to('/login')->with('login_error', 'You need to activate your account before log in.');
			}

			// The following is only required if throttle is enabled
			catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e)
			{
				return Redirect::to('/')->with('global_error', 'Depends on violation, your account has been suspended or banned.');
			}
			catch (Cartalyst\Sentry\Throttling\UserBannedException $e)
			{
				return Redirect::to('/')->with('global_error', 'Depends on violation, your account has been suspended or banned.');
			}
	}


	public function logout()
	{
		Sentry::logout();
		return Redirect::to('/')->with('global_success', 'Logged out');
	}

	public function request()
	{
	   return View::make('auth.request', array('pageTitle' => 'Password reset'));
	}

	public function requestAction()
	{
	    $user = Sentry::findUserByLogin(Input::get('email'));

		$resetCode = $user->getResetPasswordCode();

		Mail::send('emails.pass_reset', array('pass_code' => $resetCode, 'nick' => $user->nick), function($message)
					{
						$message->to(Input::get('email'), Input::get('nick'))->subject('Password reset');
					});

		return Redirect::to('/')->with('global_success', 'Link with reset authorization has been sent to your e-mail address. ');
	}


	public function reset($pass_code)
	{
		return View::make('auth.reset', array('pageTitle' => 'Password reset'))->with('pass_code', $pass_code);
	}


	public function resetAction()
	{
		// Fetch all request data.
		$data = Input::only('email', 'password', 'password_confirmation', 'pass_code');
		// Build the validation constraint set.
		$rules = array(
			'email' => array('required'),
			'password' => array('required', 'confirmed', 'min:5'),
		);

		$validator = Validator::make($data, $rules);
		if ($validator->passes())
		{
			$user = Sentry::findUserByLogin(Input::get('email'));
			// Check if the reset password code is valid
			if ($user->checkResetPasswordCode(Input::get('pass_code')))
			{
				// Attempt to reset the user password
				if ($user->attemptResetPassword(Input::get('pass_code'), Input::get('password')))
				{

					$user->reset_password_code = '';
					$user->save();

					return Redirect::to('/login')->with('global_success', 'Password has been set. You can now sign in with your new password.');

				}
				else
				{
					return Redirect::to('/reset')->with('global_error', 'System couldn\'t change your password. Please try again and if situation repeats, report to support.');
				}
			}
			else
			{
				return Redirect::to('/request')->with('global_error', 'Your reset code doesn\'t match. It may be corrupted or outdated. Please make a new request.');
			}
		}
		return Redirect::to('/reset/'.Input::get('pass_code'))->withErrors($validator)->with('message', 'Validation Errors!');
	}

}
