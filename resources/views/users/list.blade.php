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