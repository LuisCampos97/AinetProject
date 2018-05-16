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
			<th>Deleted</th>
			<th>Options</th>
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
				<td>{{ $account->name }}</td>
				<td>{{ $account->date }} </td>
				<td>{{ $account->description }} </td>
				<td>{{ $account->start_balance }}</td>
				<td>{{ $account->current_balance }}</td>
				<td>{{ $account->last_movement_date }}</td>
				<td>{{ $account->deleted_at }}</td>
				
				
				<td>
				@if(is_null($account->last_movement_date))
				<form action="{{ action('UserController@destroy', $account->id) }}" method="post" class="inline">
					@csrf
					@method('delete')
					<input type="submit" class="btn btn-xs btn-danger" value="Delete">
					</form>
				@endif
				@if(is_null($account->deleted_at))

				<form action="{{ action('UserController@closeAccount', $account->id) }}" method="post" class="inline">
					@csrf
					@method('patch')
					<input type="submit" class="btn btn-xs btn-warning" value="Close Account">
					</form>				
				@else
				<form action="{{ action('UserController@openAccount', $account->id) }}" method="post" class="inline">
					@csrf
					@method('patch')
					<input type="submit" class="btn btn-xs btn-info" value="Open Account">
					</form>		
				@endif

				
				<a class="btn btn-xs btn-success" >View Movements of Account</a> <strong style="font-size: 20px"></strong>
				</td>
			</tr>
		@endforeach
		</tbody>
@endsection
		