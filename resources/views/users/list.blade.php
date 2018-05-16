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
			<h1>{{ $pagetitle }}</h1>

			<p>
				Search name:
				<input type="text" class="form-control" id="search" name="search"></input>

				Filter:
				<a href="/?Type=Normal">Normal</a> |
				<a href="/?Type=Admin">Admin</a> |
				<a href="/?Type=Unblocked">Unblocked</a> |
				<a href="/?Type=Blocked">Blocked</a> |
				<a href="/users">Reset</a>
			</p>

			@foreach ($users as $user)
			<tr>
				<td>{{ $user->name }} </td>
				<td>{{ $user->email }} </td>
				<td>{{ $user->typeToString() }} </td>
				<td>{{ $user->blockedToString() }}</td>
				<td>
				@if ($user->blocked == 0)
				<form action="{{ route('users.block', $user->id) }}" method="post" class="inline">
					@csrf
					@method('patch')
					<input type="submit" class="btn btn-xs btn-danger" value="Block">
				</form>
				@else
				<form action="{{ route('users.unblock', $user->id) }}" method="post" class="inline">
					@csrf
					@method('patch')
					<input type="submit" class="btn btn-xs btn-success" value="UnBlock">
				</form>
				@endif
				@if ($user->admin == 0)
				<form action="{{ route('users.promote', $user->id) }}" method="post" class="inline">
					@csrf
					@method('patch')
					<input type="submit" class="btn btn-xs btn-danger" value="Promote">
				</form>
				@else
				<form action="{{ route('users.demote', $user->id) }}" method="post" class="inline">
					@csrf
					@method('patch')
					<input type="submit" class="btn btn-xs btn-success" value="Demote">
				</form>
				@endif
				</td>
			</tr>
			@endforeach
	</tbody>
</table>
</div>
@endif @endsection('content')
