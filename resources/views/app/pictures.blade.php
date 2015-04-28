@extends('layouts.master')

@section('content')


	<div class="col-md-8 col-md-offset-2 p-b-10">
		@if(Auth::user()->id == $user->id)
		Add Photos
		{!! Form::open(['route' => 'add_pictures', 'files'=>true]) !!}
			{!! Form::file('pictures[]', ['multiple' => true]) !!}
			<br>
			<button type="submit" class="btn btn-success">Post</button>
		{!! Form::close() !!}
		@else
			@if($user->provider)
				<img src="{{$user->avatar}}" height="40" width="40">
			@else
				@if($user->avatar)
					<img src="/uploads/avatar/thumbs/{{$user->avatar}}" >
				@else
					<img src="/imgs/avatar.png" height="40" width="40">
				@endif
			@endif	 
			<strong>{{$user->username}}</strong>
		@endif
	</div>
	<div class="col-md-9 col-md-offset-2">
	@if(Auth::user()->id == $user->id)
		@if(count($user->pictures) > 0)

	    	@foreach($user->pictures as $picture) 
	    	<div class="col-md-4 col-xs-12 m-b-30 border">
		    	<a href="/pictures/edit/{{$picture->id}}"><img src="/uploads/thumbs/{{ $picture->file_name }}"class="Image"></a>
		    </div>
		    @endforeach
		@else
			<h2>You don't have picture yet.</h2>
		@endif
	@else

		@if(count($user->pictures) > 0)

	    	@foreach($user->pictures as $picture) 
	    	<div class="col-md-4 col-xs-12 m-b-30 border">
		    	<img src="/uploads/thumbs/{{ $picture->file_name }}" class="Image">
		    </div>
		    @endforeach
		@else
			<h2>{{$user->username}} dones not have picture yet.</h2>
		@endif
		
	@endif

	</div>


@stop