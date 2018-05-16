@extends('layouts.app') @section('content') @if(count($users))
<div class="container">
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
			<h1>List of users</h1>

			<div class="wrap">
			<div class="search">
				<input type="text" class="searchTerm" placeholder="Search for name?" id="search" name="search">
				<button type="submit" class="searchButton">
					<i class="fa fa-search"></i>
				</button>
			</div>
			</div>

			<div>
				Filter:
				<a href="users/?type=normal">Normal</a> |
				<a href="users/?type=admin">Admin</a> |
				<a href="users/?status=unblocked">Unblocked</a> |
				<a href="users/?status=blocked">Blocked</a> |
				<a href="/users">Reset</a>

			</div>
			
			@foreach ($users as $user)
			<tr>
				<td>{{ $user->name }} </td>
				<td>{{ $user->email }} </td>
				<td>{{ $user->typeToString() }} </td>
				<td>{{ $user->blockedToString() }}</td>
				<td>
					<a class="btn btn-xs btn-primary" href="#">Block</a>
					<a class="btn btn-xs btn-primary" href="#">Promote admin</a>
				</td>
			</tr>
			@endforeach
	</tbody>
</table>
{{$users ->links()}}
</div>

@endif @endsection('content')
