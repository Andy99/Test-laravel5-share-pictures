@extends('layouts.master')

@section('content')


<div class="col-md-8 col-md-offset-2">

	<table class="table table-bordered" >
	    <tbody>
	    	@foreach($users as $user) 
		    	<tr>
		    		
		        	<td>
		        		@if($user->provider)
		        			<a href="/pictures/{{$user->username}}">
		        				<img src="{{ $user->avatar }}"  width="40" height="40" >
		        			</a>
		        		@elseif($user->avatar)
		        			<a href="/pictures/{{$user->username}}">
		        				<img src="/uploads/avatar/thumbs/{{ $user->avatar }}">
		        			</a>
		        		@else
		        			<a href="/pictures/{{$user->username}}">
		        				<img src="/imgs/avatar.png" height="40" width="40">
		        			</a>
		        		@endif
		        		<a href="/pictures/{{$user->username}}">
		        			{{ $user->username }} {{ $user->id }}
		        		</a>
		        	</td>
		        	
		        	@if($user->following['user_id'] == Auth::user()->id ) 
		        		<td><button type="button" class="btn btn-success unfollow" data-follower="{{$user->id}}" style="float:right" data-toggle="tooltip" data-placement="top" title="Unfollow"><i class="fa fa-check" data-text-swap="Follow"></i> Following</button></td>
		        	@else
		        		<td><button type="button" class="btn btn-default follow" data-follower="{{$user->id}}" style="float:right"><i class="fa fa-plus"></i> Follow</button></td>
		        	@endif

		      	</tr>
		    @endforeach
		<tbody>	
	</table>

	
</div>

@stop