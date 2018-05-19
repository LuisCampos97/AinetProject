<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
    crossorigin="anonymous"> @extends('layouts.app') @section('content')
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
				<a class="btn btn-xs btn-info" href="{{ action('UserController@accountsForUser', Auth::user()->id) }}"><i class="fas fa-book"></i>  All Accounts</a>
				<a class="btn btn-xs btn-success" href="{{ action('UserController@openedAccounts', Auth::user()->id) }}"><i class="fas fa-lock-open"></i>  Opened Accounts</a>
				<strong style="font-size: 20px">|</strong>
				<a class="btn btn-xs btn-danger" href="{{ action('UserController@closedAccounts', Auth::user()->id) }}"><i class="fas fa-lock"></i>  Closed Accounts</a>

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
					<form action="{{ action('UserController@updateAccountView', $account->id) }}" method="get" class="inline">
						<button type="submit" class="btn btn-xs btn-primary">
							<i class="fas fa-edit"></i> Update Account</button>
					</form>
					@if(is_null($account->last_movement_date))
					<form action="{{ action('UserController@destroy', $account->id) }}" method="post" class="inline">
						@csrf @method('delete')
						<button type="submit" class="btn btn-xs btn-danger">
							<i class="fas fa-trash"></i> Delete</button>
					</form>
					@endif @if(is_null($account->deleted_at))

					<form action="{{ action('UserController@closeAccount', $account->id) }}" method="post" class="inline">
						@csrf @method('patch')
						<button type="submit" class="btn btn-xs btn-warning">
							<i class="fas fa-lock"></i> Close Account</button>
					</form>
					@else
					<form action="{{ action('UserController@openAccount', $account->id) }}" method="post" class="inline">
						@csrf @method('patch')
						<button type="submit" class="btn btn-xs btn-info">
							<i class="fas fa-lock-open"></i> Open Account</button>
					</form>
					@endif

					<form action="{{ action('UserController@showMovementsForAccount', $account->id) }}" method="get" class="inline">
						<button type="submit" class="btn btn-xs btn-success"><i class="fas fa-money-bill-alt"></i>  View Account Movements</button>
					</form>
				</td>
			</tr>
			@endforeach
		</tbody>
		@endsection