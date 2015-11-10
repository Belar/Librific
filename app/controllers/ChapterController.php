<?php

use dflydev\markdown\MarkdownParser;

class ChapterController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$non_public_books_ids = DB::table('books')->where( 'public_state', '=', false)->lists('id');
		
		if($non_public_books_ids){
			$chapters = DB::table('chapters')->whereNotIn('book_id', $non_public_books_ids )->where('public_state', '=', true)->where('text', '!=', '')->orderBy( 'created_at', 'desc')->paginate(15);
		}
		else{
			$chapters = DB::table('chapters')->where('public_state', '=', true)->where('text', '!=', '')->orderBy( 'created_at', 'desc')->paginate(15);
		}
	
		return View::make('chapter.all_chapters', array('chapters' => $chapters, 'pageTitle' => 'Chapter Index'));
	}

	public function ChaptersConditions()
	{
		$data = Input::only('from', 'to');
		
		$from = Input::get('from');
		$to = Input::get('to');
		
		$rules = array(
				'to' => array('after:'.$from),
		);
		
		$validator = Validator::make($data, $rules);
		
				if ($validator->passes()) {
				
					$non_public_books_ids = DB::table('books')->where( 'public_state', '=', false)->lists('id');
				
					$chapters = DB::table('chapters')->whereNotIn('book_id', $non_public_books_ids )->where('public_state', '=', true)->where('created_at', '>=', $from)->where('created_at', '<=', $to)->orderBy('created_at', 'desc')->paginate(15);
					
					if ( $chapters->isEmpty() )
						{
						return Redirect::to('/chapters')->with('global_error', 'Sorry, we couldn\'t find any chapters which would meet your criteria.');
					}
					else {
						return View::make('chapter.all_chapters', array('chapters' => $chapters, 'pageTitle' => 'Chapter Index', 'from_old' => $from, 'to_old' => $to));
					}					
				}
				return Redirect::to('/chapters')->withInput()->withErrors($validator)->with('message', 'Validation Errors!');
	}
	
	public function ChapterTag($tag)
	{
		
		$non_public_books_ids = DB::table('books')->where( 'public_state', '=', false)->lists('id');
		
		$chapters = DB::table('chapters')->whereNotIn('book_id', $non_public_books_ids )->where('public_state', '=', true)->where('tags', 'LIKE', '%'.$tag.'%')->orderBy('created_at', 'desc')->paginate(15);
	
		return View::make('chapter.all_chapters', array('chapters' => $chapters, 'pageTitle' => 'Chapter Index'));
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
			// Fetch all request data.
			$data = Input::only('chapter_title', 'book', 'public');
			// Build the validation constraint set.
			$rules = array(
				'chapter_title' => array('required'),
				'book' => array('numeric'),
				'public' => array('integer')
				
			);
			// Create a new validator instance.
			$validator = Validator::make($data, $rules);
				if ($validator->passes()) {
									
					$chapter = new Chapter();
					$chapter->author_id = Sentry::getUser()->id;
					$title = Input::get('chapter_title');
					
					//$date = new DateTime();
					//$time = $date->format('Y-m-d-H-i-s');
					//$chapter->slug = $time.'-'.Str::slug($title, '-');
					
					$uniqid = str_shuffle(uniqid());
					$chapter->slug = Str::slug($title, '-').'-'.$uniqid;
					
					$chapter->title = $title;
					
					$chapter->secret_link = str_shuffle(uniqid());
					
					if(Input::get('book') == true){
					$chapter->book_id = Input::get('book');
					}
					
					$chapter->public_state = ( Input::get('public_state') ? 1 : 0);
					
					//Chapter cover file handle
					/*
					$file = Input::file('chapter_cover');
					$destinationPath = 'img/';
					$extension = $file->getClientOriginalExtension(); 
					$filename = $title.'.'.$extension;
					Input::file('chapter_cover')->move($destinationPath, $filename);
					*/
								
					$chapter->save();
					
					return Redirect::to('/chapter/edit/'.$chapter->id)->with('global_success', 'Chapter added successfuly!');
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
		//$chapter = Chapter::find($id);
		$chapter = Chapter::where('id', '=', $id)->orWhere('slug', '=', $id)->first();
		$book = Book::where('id', '=', $chapter->book_id)->first();
		
		if($chapter)
			{
			if ( !$book && $chapter->public_state == true)
				{
					
					$markdownParser = new MarkdownParser();
					$markdownText = $markdownParser->transformMarkdown($chapter->text);
							
					return View::make('chapter.single', array('chapter' => $chapter, 'chapter_text' => $markdownText, 'pageTitle' => 'Chapter: '.$chapter->title ));
				}
			elseif ( $chapter->public_state == true && $book && $book->public_state == true ){
					$markdownParser = new MarkdownParser();
					$markdownText = $markdownParser->transformMarkdown($chapter->text);
							
					return View::make('chapter.single', array('chapter' => $chapter, 'chapter_text' => $markdownText, 'pageTitle' => 'Chapter: '.$chapter->title ));
			}
			elseif ( Sentry::Check() && $chapter->author_id == Sentry::getUser()->id )
			{
					
					$markdownParser = new MarkdownParser();
					$markdownText = $markdownParser->transformMarkdown($chapter->text);
							
					return View::make('chapter.single', array('chapter' => $chapter, 'chapter_text' => $markdownText, 'pageTitle' => 'Chapter: '.$chapter->title ));
			}
			else
				{
					return Redirect::to('/')->with('global_error', 'You can\'t access private resources without a secret link, which can be received from creation\'s author.');
				}
		}
		return Redirect::to('/')->with('global_error', 'Sorry, chapter you are trying to reach doesn\'t exist.');
	}
	
	/*public function showSlug($slug)
	{
		$chapter = Chapter::where('id', '=', $id)->orWhere('slug', '=', $id)->first();
		
		$markdownParser = new MarkdownParser();
		$markdownText = $markdownParser->transformMarkdown($chapter->text);
				
		return View::make('chapter.single', array('chapter' => $chapter, 'chapter_text' => $markdownText ));
	}*/

	public function showPrivate($sl)
	{
		$chapter = Chapter::where('secret_link', '=', $sl)->first();
		
		if($chapter->public_state == true && Book::where( 'id', '=', $chapter->book_id)->first()->public_state == true)
			{
				return Redirect::to('/chapter/'.$chapter->slug);
			}
			
			$markdownParser = new MarkdownParser();
			$markdownText = $markdownParser->transformMarkdown($chapter->text);
								
			return View::make('chapter.single', array('chapter' => $chapter, 'chapter_text' => $markdownText, 'pageTitle' => 'Chapter: '.$chapter->title ));
		
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		
		$chapter_edit = Chapter::find($id);
	
		if (Sentry::getUser()->id === $chapter_edit->author_id )
			{
			
				return View::make('chapter.edit', array('chapter' => $chapter_edit, 'book' => $chapter_edit, 'pageTitle' => 'Chapter: '.$chapter_edit->title));
			}
		else
			{
				return Redirect::to('/dashboard')->with('global_error', 'You can\'t edit chapters of other users. See "Edit corner" below to browse your own resources.');
			}
	
	}
	
	public function editChapter()
	{
			// Fetch all request data.
			$data = Input::only('id','title', 'text', 'public', 'book', 'where', 'when', 'why');
			// Build the validation constraint set.
			$rules = array(
				'id' => array('numeric'),
				'title' => array('required'),
				'chapter_text' => array('max:21800'),
				'book' => array('numeric'),
				'public' => array('integer'),
				'where' => array('max:400'),
				'when' => array('max:400'),
				'why' => array('max:400'),
			);
			// Create a new validator instance.
			$validator = Validator::make($data, $rules);
				if ($validator->passes()) {
					
					$input = Input::all();
					
					$chapter = Chapter::find(Input::get('id'));
					$title = Input::get('title');
					
					if ($chapter->title !== $title)
						{
						//$date = new DateTime();
						//$time = $date->format('Y-m-d-H-i-s');
						//$chapter->slug = $time.'-'.Str::slug($title, '-');
						$uniqid = str_shuffle(uniqid());
						$chapter->slug = Str::slug($title, '-').'-'.$uniqid;
					}
					
					$chapter->where = Input::get('where');
					$chapter->when = Input::get('when');
					$chapter->why = Input::get('why');
					
					if ($chapter->secret_link == false)
						{
							$chapter->secret_link = str_shuffle(uniqid());
						}
					
					$chapter->title = $title;
					$chapter->text = Input::get('text');
					$chapter->tags = Input::get('tags');
					$chapter->book_id = Input::get('book');
					
					$chapter->public_state = Input::get('public');
													
					$chapter->save();
					
					return Redirect::to('chapter/'.$chapter->slug )->with('global_success', 'Congratulations! Chapter was saved.');
				}
			return Redirect::to('/chapter/edit/'.Input::get('id'))->withInput()->withErrors($validator)->with('message', 'Validation Errors!');
	}
	
	public function commentsChapter($id)
	{	
		
		$chapter_comments = Chapter::find($id);
	
		if (Sentry::getUser()->id === $chapter_comments->author_id )
			{
	
				$chapter_comments->comments_onoff = ( $chapter_comments->comments_onoff ? 0 : 1);
				$chapter_comments->save();
		
				return Redirect::back()->with('global_success', 'Comments state has been changed. Change requires refresh from other users to take effect.');
			}
		else
			{
				return Redirect::to('/dashboard')->with('global_error', 'Please, don\'t try to edit resources of other authors. See "Edit corner" below to browse your own content.');
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

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroyChapter($id)
	{	
		
		$chapter_destroy = Chapter::find($id);
	
		if (Sentry::getUser()->id === $chapter_destroy->author_id )
			{
	
				$chapter_destroy->delete();
		
				return Redirect::to('/dashboard')->with('global_success', 'Your chapter was deleted, but you can start new one with forms on your right.');
			}
		else
			{
				return Redirect::to('/dashboard')->with('global_error', 'Come on! Why would you delete not your chapter? See "Edit corner" below to browse your own resources.');
			}
	}
	
}