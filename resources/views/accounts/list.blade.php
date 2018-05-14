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
		</tr>

		<h1>List of users</h1>
	</thead>
		@foreach($accounts as $account)
		<tr>
				<td>{{ $account->account_type_id }} </td>
				<td>{{ $account->date }} </td>
				<td>{{ $account->description }} </td>
				<td>{{ $account->start_balance }}</td>
				<td>{{ $account->current_balance }}</td>
				<td>{{ $account->last_movement_date }}</td>

			</tr>
		@endforeach
	<tbody>
		