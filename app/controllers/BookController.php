<?php

class BookController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$books = DB::table('books')->where('public_state', '=', true)->where('about', '!=', ' ')->orderBy('created_at', 'desc')->paginate(15);
	
		return View::make('book.all_books', array('books' => $books, 'pageTitle' => 'Book Index'));
	}

	public function BooksConditions()
	{
		$data = Input::only('from', 'to', 'genre');
		
		$from = Input::get('from');
		$to = Input::get('to');
		$genre = Input::get('genre');
		
		$rules = array(
				'to' => array('after:'.$from),
		);
		
		$validator = Validator::make($data, $rules);
		
				if ($validator->passes()) {
				
					if ($genre == '') {
					$books = DB::table('books')->where('public_state', '=', true)->where('created_at', '>=', $from)->where('created_at', '<=', $to)->orderBy('created_at', 'desc')->paginate(15);
					}
					elseif ($from == '' || $to == '') {
					$books = DB::table('books')->where('public_state', '=', true)->where('genre', '=', $genre)->orderBy('created_at', 'desc')->paginate(15);
					}
					else{
					$books = DB::table('books')->where('public_state', '=', true)->where('created_at', '>=', $from)->where('created_at', '<=', $to)->where('genre', '=', $genre)->orderBy('created_at', 'desc')->paginate(15);
					}
					
					if ( $books->isEmpty() )
					{
						return Redirect::to('/books')->with('global_error', 'Sorry, we couldn\'t find any books which would meet your criteria.');
					}
					else {
					return View::make('book.all_books', array('books' => $books, 'pageTitle' => 'Book Index', 'from_old' => $from, 'to_old' => $to, 'genre_old' => $genre));
					}
				}
				
				return Redirect::to('/books')->withInput()->withErrors($validator)->with('message', 'Validation Errors!');
	}
	
	public function BookTag($tag)
	{

		$books = DB::table('books')->where('public_state', '=', true)->where('tags', 'LIKE', '%'.$tag.'%')->orderBy('created_at', 'desc')->paginate(15);
	
		return View::make('book.all_books', array('books' => $books, 'pageTitle' => 'Book Index'));
	}
	
	public function BookGenre($genre)
	{

		$books = DB::table('books')->where('public_state', '=', true)->where('genre', '=', $genre)->where('public_state', '=', true)->orderBy('created_at', 'desc')->paginate(15);
	
		return View::make('book.all_books', array('books' => $books, 'pageTitle' => 'Book Index'));
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
			// Fetch all request data.
			$data = Input::only('title', 'genre', 'book_cover', 'book_public_state');
			// Build the validation constraint set.
			$rules = array(
				'title' => array('required', 'min:3', 'max:100', 'unique:books'),
				'genre' => array('alpha'),
				'book_cover' => array('image'),
				'book_public_state' => array('integer')
			);
			// Create a new validator instance.
			$validator = Validator::make($data, $rules);
				if ($validator->passes()) {
					
					$book = new Book();
					$title = Input::get('title');
					$book->title = $title;
						
                    //  $date = new DateTime();
					//	$time = $date->format('Y-m-d-H-i-s');
					//$book->slug = $time.'-'.Str::slug($title, '-');
                    
                    $uniqid = str_shuffle(uniqid());
					$book->slug = Str::slug($title, '-').'-'.$uniqid;
                    
					$book->author_id = Sentry::getUser()->id;
					$book->genre = Input::get('genre');
					
					$book->secret_link = str_shuffle(uniqid());
					
					$book->public_state = ( Input::get('book_public_state') ? 1 : 0);
					
					//Book cover file handle
					/*$file = Input::file('book_cover');
					$destinationPath = 'uploads/';
					$extension = $file->getClientOriginalExtension(); 
					$filename = $title.'.'.$extension;
					Input::file('book_cover')->move($destinationPath, $filename);*/
								
					$book->save();
					return Redirect::to('/dashboard')->with('global_success', 'Book added successfuly!');
				}
			return Redirect::to('/dashboard')->withInput()->withErrors($validator)->with('message', 'Validation Errors!');
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
		$book = Book::where('id', '=', $id)->orWhere('slug', '=', $id)->first();
		$chapters = Chapter::where('book_id', '=', $book->id)->where( 'public_state', '=', true)->paginate(15);
		
		if($book)
			{
				if ( $book->public_state == true)
					{
					return View::make('book.single', array('book' => $book, 'chapters' => $chapters, 'pageTitle' => 'Book: '.$book->title));
					}
				elseif ( Sentry::Check() && $book->author_id == Sentry::getUser()->id )
				{
						
					return View::make('book.single', array('book' => $book, 'chapters' => $chapters, 'pageTitle' => 'Book: '.$book->title));
				}
				else
				{
					return Redirect::to('/')->with('global_error', 'You can\'t access private resources without a secret link, which can be received from creation\'s author.');
				}
			}
		return Redirect::to('/')->with('global_error', 'Sorry, book you are trying to reach doesn\'t exist.');

	}
	
	/*public function show($slug)
	{
		$book = Book::where('id', '=', $id)->orWhere('slug', '=', $id)->first();
		
		return View::make('book.single', array('book' => $book));
	}*/
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
	
		$book_edit = Book::find($id);
	
		if (Sentry::getUser()->id == $book_edit->author_id )
			{			
			
				return View::make('book.edit', array('book' => $book_edit, 'pageTitle' => 'Book: '.$book_edit->title));
			}
		else
			{
				return Redirect::to('/dashboard')->with('global_error', 'You can\'t edit books of other users. See "Edit corner" below to browse your own resources.');
			}

	}
	
	public function editBook(){
	
			// Fetch all request data.
			$data = Input::only('id','title', 'about', 'genre', 'tags', 'book_public_state');
			// Build the validation constraint set.
			$rules = array(
				'id' => array('numeric'),
				'title' => array('required', 'min:3', 'max:100', 'unique:books,title,'.Input::get('id')),
				'genre' => array('alpha'),
				'book_cover' => array('image'),
				'book_public_state' => array('integer'),
				'about' => array('max: 21800')
			);
			// Create a new validator instance.
			$validator = Validator::make($data, $rules);
				if ($validator->passes()) {
					
					$book = Book::find(Input::get('id'));
					$title = Input::get('title');
					
					if ($book->title !== $title)
						{
							//$date = new DateTime();
							//$time = $date->format('Y-m-d-H-i-s');
				        //$book->slug = $time.'-'.Str::slug($title, '-');
                        
                        $uniqid = str_shuffle(uniqid());
				        $book->slug = Str::slug($title, '-').'-'.$uniqid;
                        
						}
					$book->title = $title;
					
					if ($book->secret_link == false)
						{
							$book->secret_link = str_shuffle(uniqid());
						}
						
					$book->public_state = Input::get('book_public_state');
					
					$book->author_id = Sentry::getUser()->id;
					$book->about = Input::get('about');
					$book->genre = Input::get('genre');
					$book->tags = Input::get('tags');
					
					$book->save();
					
					return Redirect::to('/dashboard')->with('global_success', 'Congratulations! Book was saved.');
				}
			return Redirect::to('/book/edit')->withInput()->withErrors($validator)->with('message', 'Validation Errors!');
	
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

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroyBook($id)
	{
	
		$book_destroy = Book::find($id);
	
		if (Sentry::getUser()->id == $book_destroy->author_id )
			{

				
				foreach ( Chapter::where('book_id', '=', $book_destroy->id)->get() as $chapter )
				{
					$chapter->book_id = '';
					$chapter->save();
				}
				
				$book_destroy->delete();
				
				return Redirect::to('/dashboard')->with('global_success', 'Your book was deleted and chapters are now unassigned. You can start new one with forms on your right.');
			}
		else
			{
				return Redirect::to('/dashboard')->with('global_error', 'Come on! Why would you delete not your book? See "Edit corner" below to browse your own resources.');
			}
			
	}

}