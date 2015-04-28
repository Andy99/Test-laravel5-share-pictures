<body>
	<nav class="navbar navbar-default custom">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{{ url('/') }}">My Progect</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					@if (Auth::user())
						<li><a href="{{ url('/') }}">Home</a></li>
						<li><a href="{{ url('/search_people') }}">Search People</a></li>
						<li><a href="{{ url('/pictures') }}">Pictures</a></li>
					@endif
				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
						<li><a href="{{ url('/auth/register') }}">Register</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->first_name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								@if(!Auth::user()->provider)
								<li><a href="{{ url('/edit-profile') }}">Edit Profile</a></li>
								@endif
								<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
							</ul>

						</li>
						<li>
							@if(Auth::user()->provider)
								<img src="{{ Auth::user()->avatar }}" height="40" width="40">
								
							@else
								@if(Auth::user()->avatar)
									<img src="/uploads/avatar/{{ Auth::user()->avatar }}" height="40" width="40">
								@else
									<img src="/imgs/avatar.png" height="40" width="40">
								@endif
							@endif

						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>