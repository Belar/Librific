@extends('allround.master')

@section('content')

	<div class="row">
		<div class="col-md-8">
			<h3>What's Librific?</h3>
			<p>Librific is a text sharing platform which allows you to present your writing to fans and friends in fast, easy and comfortable way for users of all platforms. You are able to create books and split them into chapters with your own unique touch thanks to simple, yet powerful formatting tools. It doesn't matter if you want to create next big novel, share notes or a private diary, Librific is a great place to start, right now.</p>
			<p>Librific is a new project with fresh approach. Of course, as with every new thing, there is a lot more to come and there are many changes ahead. Stay tuned and take part in this journey.</p>

			<br>

			<h4>Is it free?</h4>
			<p>Yes, signing up is totally free. You can do it right now via <a href="{{{ URL::to('/register') }}}">registration form.</a></p>

			<br>

			<h4>Where should I start adding my content?</h4>
			<p>Book and chapter creation forms are available in your personal <a href="/dashboard">dashboard.</a> Via "Edit corner" tab ( in <a href="/dashboard">dashboard</a>), you can edit created earlier books and chapters, turn on/off comments for a chapter or acquire secret link to a private resource.</p>

			<h4>Can I format text of each chapter?</h4>
			<p>Yes. Thanks to <a href="http://daringfireball.net/projects/markdown">Markdown</a> support, Librific allows it's users to format their text in very powerful, yet simple way. Although panel visible in chapter editing view contains only basic options, Librific supports majority of options available in Markdown.</p>

			<h4>Can I change a template of a book or chapter?</h4>
			<p>No. It's not possible to style or edit a look of books or chapters, but that allows content posted on Librific to remain clean and simple.</p>

			<h4>My avatar is presenting grey image of unidetified person. Can I change that?</h4>
			<p>Yes, of course. Librific is using Gravatar, which holds your avatars and associate them with your e-mail address. You can create your Gravatar on the <a href="http://wwww.gravatar.com/">official website</a> or just add e-mail you used to register on Librific to your existing Gravatar account.</p>

			<br>

			<h4>What about copyrights? Can I control how public my content is?</h4>
			<p>Librific's Terms of Use don't allow copyright infringement and every person who violates those will be banned from the websites. Every registered copyrights holder can report to Librific about stolen content being published, what will result in removal of the content if that's his/her wish. It can't be controlled what people do with work presented to them, but I can ensure you that Librific doesn't claim copyrights to your work, so you have every right to ask other websites for content removal if it gets published elsewhere without your permission.</p>
			<p>To improve control over your content publicity you can make use of secret link mechanism. It will let in only people knowing the link to a private chapter, so you could share your UNpublished chapters with friends or publishers, exclusively.</p>

		</div>
		</div>
@stop
