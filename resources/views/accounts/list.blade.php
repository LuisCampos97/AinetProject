@extends('layouts.app') @section('content')
<div class="container">
<table class="table table-bordered">
	<thead class="thead-dark">
		<tr>
			<th>Account type</th>
			<th>Date</th>
			<th>Description</th>
			<th>Start Balance</th>
            <th>End Balance</th>
			<th>Last Movement</th>
			<th>Deleted </td>
		</tr>

		<h1>List of Accounts</h1>
	</thead>
	<tbody>

	<p>
	Filter:
	
		<a href="{{ action('UserController@openedAccounts', Auth::user()->id) }}">Open</a> |
		<a href="{{ action('UserController@closedAccounts', Auth::user()->id) }}">Closed</a>
	</p>

		@foreach($accounts as $account)
		<tr>
				<td>{{ $account->name }} </td>
				<td>{{ $account->date }} </td>
				<td>{{ $account->description }} </td>
				<td>{{ $account->start_balance }}</td>
				<td>{{ $account->current_balance }}</td>
				<td>{{ $account->last_movement_date }}</td>
				<td>{{ $account->deleted_at }}</td>

			</tr>
		@endforeach
		</tbody>
@endsection
		