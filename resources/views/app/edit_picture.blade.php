@extends('layouts.master')

@section('content')


<div class="col-md-8 col-md-offset-2">
	<h1>Edit Picture</h1>

</div>
	<div class="col-md-9 col-md-offset-2">

		<div class="col-md-8 p-l-0 m-b-20">
			<img src="/uploads/{{ $picture->file_name }}">
		</div>

		<div class="col-md-6 p-l-0 m-b-20">

			{!! Form::model($picture, ['route' => ['add_picture_title', $picture->id], 'method' => 'PATCH' ]) !!}
				<label>Add a title</label><br>
				<textarea name="title" rows="5" cols="50">{{ $picture->title }}</textarea>

				<div class="col-md-6 p-l-0">
					<button type="submit" class="btn btn-success">Post</button>
				</div>
			{!! Form::close() !!}

			

		</div>

		<div class="col-md-12 p-l-0">
				<a href="/picture/delete/{{$picture->id}}"><button class="btn btn-danger">Delete Picture</button></a>
			</div>
	</div>


@stop