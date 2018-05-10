@extends('layouts.app')
@section('content')
@if(count($users))
<table class="table table-stripe">
	<thead>
		<tr>
			<th>Name</th>
			<th>Email</th>
			<th>Type</th>
			<th>Status</th>
		</tr>
	</thead>
	
	<tbody>
		<div class ="Container">
		<h1>List of users</h1>

		<p>Filter:
			<a href ="/?Type=Normal">Normal</a> |
			<a href ="/?Type=Admin">Admin</a> |
			<a href ="/?Type=Unblocked">Unblocked</a> |
			<a href ="/?Type=Blocked">Blocked</a> |
			<a href ="/users">Reset</a>
		</p>

		@foreach ($users as $user)
			<tr>
				<td>{{ $user->name }} </td>
				<td>{{ $user->email }} </td>
				<td>{{ $user->typeToString() }} </td>
				<td>{{ $user->blockedToString() }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
@endif @endsection('content')
