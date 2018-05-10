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
			<p>Filter:
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
					<a class="btn btn-xs btn-primary" href="#">Block</a>
					<a class="btn btn-xs btn-primary" href="#">Promote admin</a>
				</td>
			</tr>
			@endforeach	
	</tbody>
</table>
</div>
@endif @endsection('content')