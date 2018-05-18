<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
    crossorigin="anonymous">
@extends('layouts.app') @section('content') @if(count($users))
<div class="container">

<h1>{{ $pagetitle }}</h1>
<div class="search">
	<form action="{{ route('users.search') }}" method="GET">
		<input type="text" class="searchTerm" placeholder="Search for name?" id="search" name="search">
		<button type="submit" class="searchButton">
			<i class="fa fa-search"></i> Search
		</button>
	</form>
</div>
			
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
			
			<div class="wrap">
			</div>

			<div>
				Filter:
				<a href="users/?admin=0">Normal</a> |
				<a href="users/?admin=1">Admin</a> |
				<a href="users/?blocked=0">Unblocked</a> |
				<a href="users/?status=1">Blocked</a> |
				<a href="/users">Reset</a>

			</div>
			
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
@endif @endsection('content')
