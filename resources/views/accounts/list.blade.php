<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
    crossorigin="anonymous"> @extends('layouts.app') @section('content')
<div class="container">
	<table class="table table-bordered">
		<thead class="thead-dark">
			<tr>
				<th>Code</th>
				<th>Account type</th>
				<th>Date</th>
				<th>Description</th>
				<th>Start Balance</th>
				<th>Current Balance</th>
				<th>Last Movement</th>
				<th>Deleted</th>
				<th>Options</th>
			</tr>

			<h1>{{ $pagetitle }}</h1>
		</thead>
		<tbody>
			<a class="btn btn-xs btn-success" href="{{ action('AccountController@storeAccount') }}">
				<i class="fas fa-plus"></i> Add Account</a>
			<br>
			<br>
			<p>
				<strong style="font-size: 20px">Filter: </strong>
				<a class="btn btn-xs btn-info" href="{{ action('AccountController@accountsForUser', $id) }}" data-toggle="tooltip" title="All Accounts">
					<i class="fas fa-book"></i>
				</a>
				<strong style="font-size: 20px">|</strong>
				<a class="btn btn-xs btn-success" href="{{ action('AccountController@openedAccounts', $id) }}" data-toggle="tooltip" title="Opened Accounts">
					<i class="fas fa-lock-open"></i>
				</a>
				<strong style="font-size: 20px">|</strong>
				<a class="btn btn-xs btn-danger" href="{{ action('AccountController@closedAccounts', $id) }}" data-toggle="tooltip" title="Closed Accounts">
					<i class="fas fa-lock"></i>
				</a>

			</p>

			@foreach($accounts as $account)
			<tr>
				<td>{{ $account->code }}</td>
				<td>{{ $account->name }}</td>
				<td>{{ $account->date }} </td>
				<td>{{ $account->description }} </td>
				<td>{{ $account->start_balance }}</td>
				<td>{{ $account->current_balance }}</td>
				<td>{{ $account->last_movement_date }}</td>
				<td>{{ $account->deleted_at }}</td>

				<td>
					<div class="form-group row">
						@can('change-account', $account) @if(is_null($account->deleted_at)) @if(is_null($account->last_movement_date))

						<form action="{{ action('AccountController@destroy', $account->id) }}" method="post" class="inline">
							@csrf @method('delete')
							<button type="submit" class="btn btn-danger btn-lg" data-toggle="tooltip" title="Delete">
								<i class="fas fa-trash"></i>
							</button>
						</form>
						@endif

						<form action="{{ action('AccountController@updateAccountView', $account->id) }}" method="get" class="inline">
							<button type="submit" class="btn btn-primary btn-lg" data-toggle="tooltip" title="Update Account">
								<i class="fas fa-edit"></i>
							</button>
						</form>

						<form action="{{ action('AccountController@closeAccount', $account->id) }}" method="post" class="inline">
							@csrf @method('patch')
							<button type="submit" class="btn btn-warning btn-lg" data-toggle="tooltip" title="Close Account">
								<i class="fas fa-lock"></i>
							</button>
						</form>
						<div class="container">
							<form action="{{ action('AccountController@showMovementsForAccount', $account->id) }}" method="get" class="inline">
								<button type="submit" class="btn btn-success btn-lg" data-toggle="tooltip" title="View Account Movements">
									<i class="fas fa-money-bill-alt"></i>
								</button>
							</form>
						</div>
						@else
						<form action="{{ action('AccountController@openAccount', $account->id) }}" method="post" class="inline">
							@csrf @method('patch')
							<button type="submit" class="btn btn-xs btn-info">
								<i class="fas fa-lock-open"></i> Open Account</button>
						</form> @endif
						@endcan
						
					</div>
				</td>
			</tr>
			@endforeach
		</tbody>
		@endsection