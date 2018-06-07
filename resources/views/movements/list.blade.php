<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
    crossorigin="anonymous">

@extends('layouts.app') @section('content') @if(count($movements))

<div class="container">

	<h1>{{ $pagetitle }}</h1>
	@can('change-movement', $account->id)
	<a class="btn btn-xs btn-success" href="{{ route('viewCreateMovement',$account->id) }}">
		<i class="fas fa-plus"></i> Create Movement 
	</a>
	@endcan
	<br>
	<br>

	<table class="table table-bordered">
		<thead class="thead-dark">
			<tr>
				<th> Account: {{ $account->id }} </th>
				<th>Current Balance:</th>

				<th>
					{{ current(end($movements))->end_balance }} €

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
				@can('change-movement', $account->id)
				<th>Option</th>
				@endcan
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
					@can('movement', $movement->id)
					<a class="btn btn-xs btn-success" href="{{ action('DocumentController@uploadDocumentView', $movement->id) }}">
						<i class="fas fa-plus"></i> Add Document</a>
						@endcan
					@else
					<form action="{{ action('DocumentController@viewDocument', $movement->document_id) }}" method="get" class="inline">
						<button type="submit" class="btn btn-xs btn-info" data-toggle="tooltip" title="View Document">
							<i class="fas fa-file-alt"></i> {{ $movement->original_name }}</button>
					</form>
					@can('movement', $movement->id)
					<div class="container form-group row">
						<a class="btn btn-success" href="{{ action('DocumentController@uploadDocumentView', $movement->id) }}" data-toggle="tooltip"
						    title="Change Document"><i class="fas fa-exchange-alt"></i></a>
						<form action="{{ action('DocumentController@removeDocument', $movement->document_id) }}" method="post" class="inline">
							@csrf @method('delete')
							<button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Remove Document">
								<i class="fas fa-trash"></i>
							</button>
						</form>
					</div>
					@endcan
					@endif
				</td>
				@can('change-movement', $account->id)
				<td>
					<form action="{{ action('MovementController@deleteMovement', $movement->id) }}" method="post" class="inline">
						@csrf @method('delete')
						<button type="submit" class="btn btn-xs btn-danger">
							<i class="fas fa-trash"></i> Delete</button>
					</form>

					<a href="{{ action('MovementController@renderViewUpdateMovement', $movement->id) }}" class="inline">
						<button type="submit" class="btn btn-xs btn-info">
							<i class="fas fa-list"></i> Update Movement</button>
					</a>
				</td>
				@endcan
			</tr>
			@endforeach
			
			</tbody>
		</table>
	</div>
		@else
		<div class="container">
		<h1>{{ $pagetitle }}</h1>
			@can('change-movement', $account->id)
			<a class="btn btn-xs btn-success" href="{{ action('MovementController@viewCreateMovement',$account->id) }}">
				<i class="fas fa-plus"></i> Create Movement </a>
				@endcan
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