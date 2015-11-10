<?php

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('user.register', array('pageTitle' => 'Registration'));
	}
	
	public function registerUser()
	{
		// Fetch all request data.
		$data = Input::only('nick', 'email', 'password', 'password_confirmation', 'tos');
		// Build the validation constraint set.
		$rules = array(
			'nick' => array('required', 'min:3', 'max:32', 'unique:users,nick', 'not_in:admin,Admin,Moderator,Mod,Administrator'),
			'email' => array('required', 'email', 'unique:users,email'),
			'password' => array('required', 'confirmed', 'min:5'),
			'tos' => array('accepted'),			
		);
		// Create a new validator instance.
		$validator = Validator::make($data, $rules);
			if ($validator->passes() ) {
				
				$user = new User();
				$user->nick = Input::get('nick');
				$user->email = Input::get('email');
				$user->password = Hash::make(Input::get('password'));
				$activation_code = md5(uniqid(rand(1000, 6000), true));
				$user->activation_code = $activation_code;
				
				$activation_code_encrypted = Crypt::encrypt($activation_code);
				Mail::send('emails.activate', array('activation_code' => $activation_code_encrypted, 'nick' => Input::get('nick')), function($message)
					{
						$message->to(Input::get('email'), Input::get('nick'))->subject('Welcome!');
					});
				
				$user->save();
				
				
				return Redirect::route('home')->with('global_success', 'Activation code has been sent to your email address.');
			}
		return Redirect::to('/register')->withInput()->withErrors($validator)->with('message', 'Validation Errors!');
	}
	
	public function activateUser($activation_code)
	{
		$activation_code = Crypt::decrypt($activation_code);
		$active = User::where('activation_code', '=', $activation_code)->first();
		$active->activated = '1';
		$active->activated_at = new DateTime;
		$active->save();

		return Redirect::to('/login')->with('global_success', 'Your profile is now active and you can sign in.');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}
	
	public function profile($nick)
	{			
		$profile = User::where('nick', '=', $nick)->first();
		$avatar = md5( strtolower( trim( $profile->email ) ) );
	
		return View::make('user.profile', array('profile' => $profile, 'avatar' => $avatar, 'pageTitle' => $profile->nick));
		
	}
	
	/**
	 * Dashboard for user's content management, only for auth user.
	 */
	public function dashboard()
	{	
		$user = User::find(Sentry::getUser()->id);
		$non_public_books_ids = DB::table('books')->where( 'public_state', '=', false)->lists('id');
		return View::make('user.dashboard', array('user' => $user, 'non_public_books_ids' => $non_public_books_ids, 'pageTitle' => 'Dashboard' ));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit()
	{
		$user_edit = User::find(Sentry::getUser()->id);
		
		return View::make('user.profile_edit', array('user' => $user_edit, 'pageTitle' => 'Profile editing'));
	}
	
	public function editUser()
	{
		$data = Input::only('id','email','twitter', 'facebook', 'goodreads', 'youtube', 'website', 'about');
	
		$rules = array(
				'id' => array('numeric'),
				'email' => array('required', 'email', 'unique:users,email,'.Input::get('id')),
				'website' => array('url'),
				'twitter' => array('url'),
				'facebook' => array('url'),
				'youtube' => array('url'),
				'goodreads' => array('numeric'),
				'about' => array('max: 21800'),
			);
			// Create a new validator instance.
			$validator = Validator::make($data, $rules);
				if ($validator->passes()) {
					
					$user = User::find(Input::get('id'));
					$user->email = Input::get('email');
					//$user->disqus = Input::get('disqus');
					$user->website = Input::get('website');
					$user->twitter = Input::get('twitter');
					$user->facebook = Input::get('facebook');
					$user->goodreads = Input::get('goodreads');
					$user->youtube = Input::get('youtube');
					$user->about = Input::get('about');
					$user->save();
					
					return Redirect::to('/profile_edit')->with('global_success', 'Profile changes have been saved.');
				}
		return Redirect::to('/profile_edit')->withInput()->withErrors($validator)->with('message', 'Validation Errors!');
	}
	
	/*Password change*/
	
	public function passChange()
	{
		$user_pass_change = User::find( Sentry::getUser()->id );
		
		return View::make('user.pass_change', array('user' => $user_pass_change, 'pageTitle' => 'Password changing'));
	}
	
	public function passChangeAction()
	{
		// Fetch all request data.
		$data = Input::only('password', 'password_confirmation', 'old_password');
		
		if (
		Auth::attempt(
					array(
						'password' => Input::get('old_password')
						)
					)
			)
		{
			$rules = array(
				'password' => array('required', 'confirmed', 'different:old_password', 'min:5')
			);
			// Create a new validator instance.
			$validator = Validator::make($data, $rules);
				if ($validator->passes()) {
					
					$user = User::find(Input::get('id'));
					$user->password = Hash::make(Input::get('password'));
					$user->save();
					
					return Redirect::route('home');
				}
			return Redirect::to('/pass_change')->withInput()->withErrors($validator)->with('message', 'Validation Errors!');
		}
		else {
			//Return after failed authentication with old pass
			return Redirect::to('/pass_change')->with('old_pass_error', 'Your old password is incorrect.');
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}
	
	public function suspendUser($id)
	{
		$user = Sentry::findUserByID($id);
		if (!$user->hasAccess('admin'))
		{
			try
			{
				// Find the user using the user id
				$throttle = Sentry::findThrottlerByUserId($id);

				// Suspend the user
				$throttle->suspend(15);

				return Redirect::to('/wonderland')->with('global_success', 'User suspended successfully for 15 minutes.');
			}
			catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
			{
				return Redirect::to('/wonderland')->with('global_error', 'There is no such user.');
			}
		}
		else
		{
			return Redirect::to('/wonderland')->with('global_error', 'You can\'t suspend Admin');
		}
	
	}
	
	public function unsuspendUser($id)
	{
		try
		{
			// Find the user using the user id
			$throttle = Sentry::findThrottlerByUserId($id);

			// Unsuspend the user
			$throttle->unsuspend();
			
			return Redirect::to('/wonderland')->with('global_success', 'User UNsuspended successfully.');
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			return Redirect::to('/wonderland')->with('global_error', 'There is no such user.');
		}
	}
	
	public function banUser($id)
	{
		$user = Sentry::findUserByID($id);
		if (!$user->hasAccess('admin'))
		{
			try
			{
				// Find the user using the user id
				$throttle = Sentry::findThrottlerByUserId($id);

				// Ban the user
				$throttle->ban();
				
				return Redirect::to('/wonderland')->with('global_success', 'User banned successfully banned.');
			}
			catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
			{
				return Redirect::to('/wonderland')->with('global_error', 'There is no such user.');
			}
		}
		else
		{
			return Redirect::to('/wonderland')->with('global_error', 'You can\'t ban Admin');
		}
	
	}
	
	public function unbanUser($id)
	{
		try
		{
			// Find the user using the user id
			$throttle = Sentry::findThrottlerByUserId($id);

			// Unban the user
			$throttle->unban();
			
			return Redirect::to('/wonderland')->with('global_success', 'User UNbanned successfully.');
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			return Redirect::to('/wonderland')->with('global_error', 'There is no such user.');
		}
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
	
	public function follow($id)
	{
		$follower = Auth::User();
		$follower->follow()->attach(User::find($id));
		
		return Redirect::to(URL::previous());
	}
	
	public function unfollow($id)
	{
		$follower = Auth::User();
		$follower->follow()->detach(User::find($id));
		return Redirect::to(URL::previous());
	}
	
	public function favourite($id)
	{
		$user = Auth::User();
		$fav_check = DB::table('favourite_ch')->where('user_id', '=', $user->id)->where('favourite_id', '=', $id)->first();
		
		if($fav_check == false)
		{
			$favourite = $user->favouriteCh()->attach(Chapter::find($id), array('created_at' => new DateTime));
			
			return Redirect::to(URL::previous())->with('global_success', 'Chapter has been added to favourites.');
		}
		else
		{
			return Redirect::to(URL::to('/chapter/'.Chapter::find($id)->slug))->with('global_error', 'This chapter already exists in your favourites.');
		}
	}
	
	public function unfavourite($id)
	{
		$user = Auth::User();
		$fav_check = DB::table('favourite_ch')->where('user_id', '=', $user->id)->where('favourite_id', '=', $id)->first();
		
		if($fav_check == false)
		{
			return Redirect::to(URL::to('/chapter/'.Chapter::find($id)->slug))->with('global_error', 'This chapter doesn\'t exists in your favourites.');
		}
		else
		{
			$user->favouriteCh()->detach(Chapter::find($id));	
			return Redirect::to(URL::previous())->with('global_success', 'Chapter has been removed from favourites.');
		}
	}

}