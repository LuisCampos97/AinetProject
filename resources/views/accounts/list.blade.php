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

		<h1>{{ $pagetitle }}</h1>
	</thead>
	<tbody>

	<p>
	<strong style="font-size: 20px">Filter: </strong>
	
		<a class="btn btn-xs btn-success" href="{{ action('UserController@openedAccounts', Auth::user()->id) }}">Open</a> <strong style="font-size: 20px">|</strong>
		<a class="btn btn-xs btn-danger" href="{{ action('UserController@closedAccounts', Auth::user()->id) }}">Closed</a>
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
		