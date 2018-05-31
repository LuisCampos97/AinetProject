<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
    crossorigin="anonymous"> @extends('layouts.app') @section('content') @if(count($movements))

<div class="container">

	<h1>{{ $pagetitle }}</h1>
	<a class="btn btn-xs btn-success" href="{{ action('MovementController@viewCreateMovement',$account->id) }}">
		<i class="fas fa-plus"></i> Create Movement </a>
	<br>
	<br>

	<table class="table table-bordered">
		<thead class="thead-dark">
			<tr>
				<th> Account: {{ $account->id }} </th>
				<th>Current Balance:</th>

				<th>
					{{$account->current_balance}} €

				</th>
			</tr>
		</thead>
	</table>
	<table class="table table-bordered">
		<thead class="thead-dark">
			<tr>
				<th>Category</th>
				<th>Date</th>
				<th>Value</th>
				<th>Type</th>
				<th>Start Balance</th>
				<th>End Balance</th>
				<th>Document</th>
				<th>Option</th>
			</tr>
			<br>

		</thead>

		<tbody>
			@foreach($movements as $movement)
			<tr>
				<td>{{ $movement->name }}</td>
				<td>{{ $movement->date }} </td>
				<td>
					<strong>{{ $movement->value }} €</strong>
				</td>
				<td>{{ $movement->type }}</td>

				<td>{{ $movement->start_balance}} €</td>
				<td>{{ $movement->end_balance }} €</td>
				<td>
					@if(is_null($movement->original_name))
					<a class="btn btn-xs btn-success" href="{{ action('DocumentController@uploadDocumentView', $movement->id) }}">
						<i class="fas fa-plus"></i> Add Document</a>
					@else {{ $movement->original_name }}
					<form action="{{ action('DocumentController@removeDocument', $movement->document_id) }}" method="post" class="inline">
						@csrf @method('delete')
						<button type="submit" class="btn btn-danger btn-lg" data-toggle="tooltip" title="Remove Document">
							<i class="fas fa-trash"></i>
						</button>
					</form>
					@endif
				</td>
				<td>
					<form action="{{ action('MovementController@deleteMovement', [$account->id, $movement->id]) }}" method="post" class="inline">
						@csrf @method('delete')
						<button type="submit" class="btn btn-xs btn-danger">
							<i class="fas fa-trash"></i> Delete</button>
					</form>

					<form action="{{ action('MovementController@renderViewUpdateMovement', [$account->id, $movement->id]) }}" method="get" class="inline">
						<button type="submit" class="btn btn-xs btn-info">
							<i class="fas fa-list"></i> Update Movement</button>
					</form>
				</td>
			</tr>
			@endforeach
		</tbody>
		@else
		<div class="container">
			<a class="btn btn-xs btn-success" href="{{ action('MovementController@viewCreateMovement',$account->id) }}">
				<i class="fas fa-plus"></i> Create Movement </a>
			<br>
			<br>
			<table class="table table-bordered">
				<thead class="thead-dark">
					<tr>
						<th> Account: {{ $account->id }} </th>
						<th>Current Balance:</th>

						<th>
							{{$account->current_balance}} €
						</th>
					</tr>
				</thead>
			</table>
		</div>
		@endif @endsection