@extends('allround.master')

@section('content')
	<div id="chapter-edit" class="row">
	
		<div class="col-md-4">
		
			<h4>Secret link</h4>
			<p>This chapter is set to be a private content, but you can still show it to whoever you want by passing them secret link visible below.</p>
				<input class="well well-sm secret_link form-control"  value="{{{ URL::to('/').'/chapter/private/'.$chapter->secret_link }}}"></input>
			<p><strong>Note:</strong> Private resources stay invisible across Librific until set to Public. It means they won't be visible on Published board, in tables of contents and public chapter index.</p>


			<h4>Edit view</h4>
			<p>Edit view allows you to edit content of your chapter, but also add simple, yet powerful formatting information which will change it into userfriendly, easy to read portion of text.</p>
			
			<p>Thanks to Markdown support, you can edit your chapter formating to fit your needs with easy use of panel above edit field. It contains basic options like:</p>
			
			<ul id="edit-options">
				<li><span class="fa fa-bold"></span> bold,</li>
				<li><span class="fa fa-italic"></span> italic,</li>
				<li><span class="fa fa-font"></span> heading,</li>
				<li><span class="fa fa-globe"></span> link,</li>
				<li><span class="fa fa-picture-o"></span> image insert,</li>
				<li><span class="fa fa-list"></span> and list,</li>
			</ul>
			<p>but also allows you to preview your text before you publish it.</p>	
			<p>If basic functions from editor's panel are not enough for you, why not dive into <a href="http://daringfireball.net/projects/markdown/syntax">Markdown syntax yourself?</a> Librific fully supports all Markdown options, if not all of them.</p>
		</div>
	
		<div class="col-md-8">
			<h3 class="text-center">{{{ $chapter->title }}}</h3>

				<ul class="errors">
					@foreach($errors->all() as $message)
						<li class="text-danger">{{ $message }}</li>
					@endforeach
				</ul>

			{{ Form::open(array('url' => 'chapter_edit', 'method' => 'PUT')) }}
			
				<div class="form-group">
				{{ Form::hidden('id', $chapter->id) }}
				</div>
				<div class="form-group">
				{{ Form::label('title', 'Title') }} <p><small>Changing title will regenerate slug-link to this chapter.</small></p>
				{{ Form::text('title', $chapter->title, array('class'=>'form-control') ) }}
				</div>
				<div class="form-group">
				{{ Form::label('text', 'Text') }}
				{{ Form::textarea('text', $chapter->text, array('id' => 'chapter-editor', 'class'=>'form-control', 'data-provide'=>'markdown' ) ) }}
				</div>
				
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
							{{ Form::label('where', 'Where') }} <p><small>Where did you write this piece? Max. 200 characters</small></p>
							{{ Form::text('where', $chapter->where, array('class'=>'form-control') ) }}
							</div>				
						</div>
						<div class="col-md-6">
							<div class="form-group">
							{{ Form::label('when', 'When') }} <p><small>When was it? 01-01-2001, rainy fall, today? Max. 200 characters</small></p>
							{{ Form::text('when', $chapter->when, array('class'=>'form-control') ) }}
							</div>							
						</div>						
					</div>
				
				<div class="form-group">
				{{ Form::label('why', 'Why') }} <p><small>Why did you write it? Be it emotional reaction, reasonable call, share your inspiration and motivation. Max 400 characters</small></p>
				{{ Form::textarea('why', $chapter->why, array('class'=>'form-control chapter-why') ) }}
				</div>
				
				<div class="form-group">
				{{ Form::label('book', 'Book') }}		
				{{ Form::select('book', array(''=>'') + Book::where('author_id', '=', $chapter->author_id )->orderBy('created_at', 'desc')->lists('title', 'id'), isset($chapter->book_id) ? $chapter->book_id : '' , array('class'=>'form-control')) }}
				</div>
				<div class="form-group">
				{{ Form::label('tags', 'Tags') }} <p><small>Use commas to separate tags from each other.</small></p>
				{{ Form::text('tags', $chapter->tags, array('class'=>'form-control') ) }}
				</div>
				<div class="checkbox">
						{{ Form::checkbox('public', '1', $chapter->public_state ) }} <p>Public - <small>If set to private (unmarked), you will still be able to share your content with a secret link, but it won't be visible for wide audience and listed in public resources.</small></p>
				</div>
			
				{{-- Form submit button. --------------------}}
				{{ Form::submit('Update', array('class' => 'btn btn-primary')) }}
			
			{{ Form::close() }}
		</div>

	</div>
@stop
	
@section('footer')
	<script>
		$("#chapter-editor").markdown({
			additionalButtons: [
				[{
					name: "groupCustom",
					data: [{
						name: "cmdCode",
						title: "Code",
						icon: "fa fa-code",
						callback: function(e){
							var chunk, cursor, selected = e.getSelection(), content = e.getContent()

							if (selected.length == 0) {
							  // Give extra word
							  chunk = 'Insert code block'
							} else {
							  chunk = selected.text
							}

							// transform selection and set the cursor into chunked text
							if (content.substr(selected.start-1,1) == '\n    ' 
								&& content.substr(selected.end,1) == '\n' ) {
							  e.setSelection(selected.start-1,selected.end+1)
							  e.replaceSelection(chunk)
							  cursor = selected.start-1
							} else {
							  e.replaceSelection('\n    '+chunk+'\n')
							  cursor = selected.start+1
							}

							// Set the cursor
							e.setSelection(cursor,cursor+chunk.length)
						}
					  }]
				}]
			  ]
			})
	</script>
	<script>
		$(".secret_link").on("click", function () {
		   $(this).select();
		});
	</script>
@stop		
