<?php

use dflydev\markdown\MarkdownParser;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', array( 'as' => 'home', function ()
{
	return View::make('allround.hello' , array('pageTitle' => 'Home'));
}
));

Route::get('/about', array( 'as' => 'about', function ()
{
	return View::make('allround.about' , array('pageTitle' => 'About'));
}
));

Route::get('/faq', array( 'as' => 'faq', function ()
{
	return View::make('allround.faq' , array('pageTitle' => 'FAQ'));
}
));

Route::get('/tos', array( 'as' => 'tos', function ()
{
	return View::make('allround.tos' , array('pageTitle' => 'Terms of Service'));
}
));

// Route::get('/pdf/{id}', array( 'as' => 'pdf', function ($id)
// {
		// //$chapter = Chapter::find($id);
		// $chapter = Chapter::where('id', '=', $id)->orWhere('slug', '=', $id)->first();

		// if($chapter)
			// {
			// if ( $chapter->public_state == true && Book::where( 'id', '=', $chapter->book_id) == true)
				// {

					// $markdownParser = new MarkdownParser();
					// $markdownText = $markdownParser->transformMarkdown($chapter->text);
					// return PDF::load($markdownText, 'A4', 'portrait')->show();
				// }
			// elseif ( Auth::Check() && $chapter->authSentryor_id == Auth::User()->id )
			// {

					// $markdownParser = new MarkdownParser();
					// $markdownText = $markdownParser->transformMarkdown($chapter->text);

					// return PDF::load($markdownText, 'A4', 'portrait')->show();
			// }
			// else
				// {
					// return Redirect::to('/')->with('global_error', 'You can\'t access private resources without a secret link, which can be received from creation\'s authSentryor.');
				// }
		// }
		// return Redirect::to('/')->with('global_error', 'Sorry, chapter you are trying to reach doesn\'t exist.');
// }
// ));

//Blog scraps
Route::get('/libribook', array( 'as' => 'libribook', function ()
{
	$recent = Chapter::whereRaw('author_id = ? AND book_id = ? AND public_state = ?',array('1', '1', true))->orderBy('created_at','desc')->first();
	$markdownParser = new MarkdownParser();
	$markdownText = $markdownParser->transformMarkdown($recent->text);

	$chapters = Chapter::whereRaw('author_id = ? AND book_id = ? AND public_state = ?',array('1', '1' ,true))->orderBy('created_at','desc')->paginate(10);

	return View::make('allround.libribook' , array('pageTitle' => 'Libri-book', 'recent' => $recent , 'recent_text' => $markdownText ,'chapters' => $chapters));
}
));

//Route::group(array('https' => 'true', 'before' => 'ssl'), function() Use for SSL
//{

Route::get('user', 'UserController');

	Route::get('register', 'UserController@create');
	Route::post('register', array('before' => 'csrf' ,'uses' => 'UserController@registerUser'));
	Route::get('dashboard', array('before' => 'authSentry', 'uses' => 'UserController@dashboard'));
	Route::get('profile/{nick}', array( 'uses' => 'UserController@profile'));
	Route::get('profile_edit', array('before' => 'authSentry', 'uses' => 'UserController@edit'));
	Route::put('profile_edit', array('before' => 'authSentry, csrf', 'uses' => 'UserController@editUser'));
	Route::get('pass_change', array('before' => 'authSentry', 'uses' => 'UserController@passChange'));
	Route::put('pass_change', array('before' => 'authSentry, csrf', 'uses' => 'UserController@passChangeAction'));
	Route::get('profile/follow/{id}', array('before' => 'authSentry', 'uses' => 'UserController@follow'));
	Route::get('profile/unfollow/{id}', array('before' => 'authSentry', 'uses' => 'UserController@unfollow'));
	Route::get('activate/{activation_code}', array('uses' => 'UserController@activateUser'));


Route::get('authSentry', 'AuthController');

	Route::get('login', 'AuthController@login');
	Route::post('login', array('before' => 'csrf' ,'uses' =>'AuthController@loginTry'));
	Route::get('logout', array('before' => 'authSentry', 'uses' => 'AuthController@logout'));

	Route::get("/request", array("uses" => "AuthController@request"));
	Route::post("/request", array('before'=>'csrf', "uses" => "AuthController@requestAction"));

	Route::get("/reset/{pass_code}", array("uses" => "AuthController@reset"));
	Route::post("/reset", array('before'=>'csrf', "uses" => "AuthController@resetAction"));

Route::get('book', 'BookController');

	Route::post('add_book', array('before' => 'csrf' ,'uses' =>'BookController@create'));
	Route::get('book', 'BookController@index');
	Route::get('books', 'BookController@index');
	Route::post('books_conditions', 'BookController@BooksConditions');
	Route::get('book_tag/{tag}', 'BookController@BookTag');
	Route::get('book_genre/{genre}', 'BookController@BookGenre');
	Route::get('book/{id}', 'BookController@show');
	Route::get('book/edit/{id}', array('before' => 'authSentry', 'uses' => 'BookController@edit'));
	Route::put('book_edit', array('before' => 'authSentry, csrf', 'uses' => 'BookController@editBook'));
	Route::get('book/delete/{id}', array('before' => 'authSentry', 'uses' => 'BookController@destroyBook'));

Route::get('chapter', 'ChapterController');

	Route::post('add_chapter', array('before' => 'csrf' ,'uses' =>'ChapterController@create'));
	Route::get('chapter', 'ChapterController@index');
	Route::get('chapters', 'ChapterController@index');
	Route::post('chapters_conditions', 'ChapterController@ChaptersConditions');
	Route::get('chapter_tag/{tag}', 'ChapterController@ChapterTag');
	Route::get('chapter/{id}', array('uses' => 'ChapterController@show'));
	Route::get('chapter/private/{sl}', array('uses' => 'ChapterController@showPrivate'));
	Route::get('chapter/edit/{id}', array('before' => 'authSentry', 'uses' => 'ChapterController@edit'));
	Route::put('chapter_edit', array('before' => 'authSentry, csrf', 'uses' => 'ChapterController@editChapter'));
	Route::get('chapter/delete/{id}', array('before' => 'authSentry', 'uses' => 'ChapterController@destroyChapter'));
	Route::get('chapter/comments/{id}', array('before' => 'authSentry', 'uses' => 'ChapterController@commentsChapter'));
	/*This one is tricky, favourite chapter isn't for chapter. It depends on USER*/
	Route::get('chapter/favourite/{id}', array('before' => 'authSentry', 'uses' => 'UserController@favourite'));
	Route::get('chapter/unfavourite/{id}', array('before' => 'authSentry', 'uses' => 'UserController@unfavourite'));

//}); Use with SSL



Route::group(array('before' => 'admin'), function()
{
	Route::get('admin', 'AdminController');

	Route::get('admin', 'AdminController@index');
	Route::get('user/suspend/{id}', 'UserController@suspendUser');
	Route::get('user/unsuspend/{id}', 'UserController@unsuspendUser');
	Route::get('user/ban/{id}', 'UserController@banUser');
	Route::get('user/unban/{id}', 'UserController@unbanUser');

});
