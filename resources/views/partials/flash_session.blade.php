@if(Session::has('error_message'))
    <div class="alert alert-danger" role="alert">
        <strong>{!!  Session::get('error_message') !!}</strong>
    </div>
@endif

@if(Session::has('success_message'))
    <div class="alert alert-success" role="alert">
        <strong>{!!  Session::get('success_message') !!}</strong>
    </div>
@endif

@if (count($errors) > 0)
	<div class="alert alert-danger">
		<strong>Whoops!</strong> There were some problems with your input.<br><br>
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif
