@extends('layouts.master')

@section('content')


	<input type="hidden" name="rows" value="{{$rows}}" id="count-rows">
	<div class="col-md-8 col-md-offset-2 main-div" id="container">
	@if($followings)
		@foreach($followings as $following)
		<div class="main-img-container">
			<div class="col-md-12 col-md-offset-6 img-container grey-one">
				@if($following->user_id->provider)
					<img src="{{ $following->user_id->avatar }}" height="40" width="40">
				@else
					@if($following->user_id->avatar)
						<img src="/uploads/avatar/thumbs/{{$following->user_id->avatar}}" class="element">
					@else
						<img src="/imgs/avatar.png" height="40" width="40">
					@endif
				@endif
					<p class="fl-left">{{$following->user_id->username}}</p>
					<abbr class="timeago fl-right" title="{{ gmdate("Y-m-d\TH:i:s\Z",strtotime($following->created_at)) }}"></abbr>
			</div>

			<div class="col-md-10 col-md-offset-6 img-container">
				<img src="uploads/{{$following->file_name}}" class="Image">
			</div>

			<div class="col-md-12 col-md-offset-6 img-container post-container">
				<p><strong>{{$following->title}}</strong></p>
			</div>
		</div>
		<div class="br"></div>
		@endforeach
	@else
		<div class="col-md-12 col-md-offset-6 img-container">
			<h2>No image to show yet.</h2>
		</div>
	@endif
	</div>

	<div class="modal"></div>

@stop



