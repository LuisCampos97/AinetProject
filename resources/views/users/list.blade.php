<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
    crossorigin="anonymous">
@extends('layouts.app') @section('content') 
<div class="container">

<p>
<h1>{{ $pagetitle }}</h1>
<div class="search">
	<form action="{{ route('users.search') }}" method="GET">
		<input type="text" class="searchTerm" placeholder="Search for name?" id="search" name="search">
		<button type="submit" class="searchButton">
			<i class="fa fa-search"></i> Search
		</button>
	</form>
</div>
</p>

<p>
<div>
	<strong style="font-size: 20px">Filter:</strong>
	<a class="btn btn-xs btn-success" href="{{ action('UserController@normalUser') }}">Normal</a> 
	<a class="btn btn-xs btn-success" href="{{ action('UserController@adminUser') }}">Admin</a>
	<a class="btn btn-xs btn-success" href="{{ action('UserController@unblockedUser') }}">Unblocked</a>
	<a class="btn btn-xs btn-success" href="{{ action('UserController@blockedUser') }}">Blocked</a>
	<a class="btn btn-xs btn-success" href="{{ action('UserController@index') }}">Reset</a>
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
				<div class="form-group">
				<form action="{{ route('users.block', $user->id) }}" method="post" class="inline">
					@csrf
					@method('patch')
					<button type="submit" class="btn btn-xs btn-danger"><i class="fas fa-lock"></i> Block</button>
				</form>
				@else
				<form action="{{ route('users.unblock', $user->id) }}" method="post" class="inline">
					@csrf
					@method('patch')
					<button type="submit" class="btn btn-xs btn-success"><i class="fas fa-unlock"></i> Unblock</button>
				</form>
				@endif
				@if ($user->admin == 0)
				<form action="{{ route('users.promote', $user->id) }}" method="post" class="inline">
					@csrf
					@method('patch')
					<button type="submit" class="btn btn-xs btn-success"><i class="fas fa-user-tie"></i> Promote</button>
				</form>
				@else
				<form action="{{ route('users.demote', $user->id) }}" method="post" class="inline">
					@csrf
					@method('patch')
					<button type="submit" class="btn btn-xs btn-danger"><i class="fas fa-user-minus"></i> Demote</button>
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
