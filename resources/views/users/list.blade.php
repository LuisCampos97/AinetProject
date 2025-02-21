<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
    crossorigin="anonymous">
@extends('layouts.app') @section('content') 
<div class="container">

<p>
<h1>{{ $pagetitle }}</h1>
<div class="search">
	<form action="{{ route('users') }}" method="GET">
		<input type="text" class="searchTerm" placeholder="Search for name?" id="search" name="name">
			<button type="submit" class="searchButton">
				<i class="fa fa-search"></i> Search
			</button>
	</form>
</div>
</p>

<p>
<div>
	<strong style="font-size: 20px">Filter:</strong>
	<a class="btn btn-xs btn-info" href="/users?type=normal">Normal</a>
	<a class="btn btn-xs btn-info" href="/users?type=admin">Admin</a>
	<a class="btn btn-xs btn-info" href="/users?status=unblocked">Unblocked</a>
	<a class="btn btn-xs btn-info" href="/users?status=blocked">Blocked</a>
	<a class="btn btn-xs btn-success" href="{{ action('UserController@index') }}"><i class="fas fa-redo"></i> Reset</a>
</div>
</p>

@if(count($users))
<table class="table table-bordered">
	<thead class="thead-dark">
		<tr>
			<th>Name</th>
			<th>Email</th>
			<th>Type</th>
			<th>Status</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($users as $user)
			<tr>
				<td>{{ $user->name }} </td>
				<td>{{ $user->email }} </td>
				<td>{{ $user->typeToString() }} </td>
				<td>{{ $user->blockedToString() }}</td>
				<td>
				@if ($user->id != Auth::user()->id)
				@if ($user->blocked == 0)
				<div class="form-group row">
				<form action="{{ route('users.block', $user->id) }}" method="post" class="inline">
					@csrf
					@method('patch')
					<button type="submit" class="btn btn-danger btn-lg" data-toggle="tooltip" title="Block"><i class="fas fa-lock"></i></button>
					</br>
				</form>
				@else
				<form action="{{ route('users.unblock', $user->id) }}" method="post" class="inline">
					@csrf
					@method('patch')
					<button type="submit" class="btn btn-success btn-lg" data-toggle="tooltip" title="Unblock"><i class="fas fa-unlock"></i></button>
					</br>
				</form>
				@endif
				@if ($user->admin == 0)
				<form action="{{ route('users.promote', $user->id) }}" method="post" class="inline">
					@csrf
					@method('patch')
					<button type="submit" class="btn btn-success btn-lg" data-toggle="tooltip" title="Promote"><i class="fas fa-user-tie"></i></button>
					</br>
				</form>
				@else
				<form action="{{ route('users.demote', $user->id) }}" method="post" class="inline">
					@csrf
					@method('patch')
					<button type="submit" class="btn btn-danger btn-lg" data-toggle="tooltip" title="Demote"><i class="fas fa-user-minus"></i></button>
					</br>
				</form>
				</div>
				@endif
				@endif
				</td>
			</tr>
			@endforeach
	</tbody>
</table>
{{$users ->links()}}
</div>
@else
<p></p>
<h1>Your search found no matches!</h1>
@endif
 @endsection('content')
