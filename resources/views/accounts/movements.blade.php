<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
    crossorigin="anonymous">

@extends('layouts.app') @section('content') @if(count($movements))

<div class="container">

<h1>{{ $pagetitle }}</h1>
<a class="btn btn-xs btn-success" href="{{ action('UserController@viewCreateMovement',$id) }}"> <i class="fas fa-plus"></i> Create Movement </a>
<br><br>

<table class="table table-bordered">
<thead class="thead-dark">
	<tr>
		<th> Account: {{ $id }} </th> <th>Current Balance:</th> 
		
		<th>
		{{$movements[0]->end_balance}} €
		</th>
	</tr>
</thead>
</table>

<table class="table table-bordered">
	<thead class="thead-dark">
		<tr>
			<th>ID</th>
			<th>Category</th>
			<th>Date</th>
			<th>Value</th>
			<th>Type</th>
            <th>End Balance</th>
		</tr>

		
		<br>
		
	</thead>
	
    <tbody>
    @foreach($movements as $movement)
		<tr>
				<td>{{ $movement->account_id}}</td>
				<td>{{ $movement->name }}</td>
				<td>{{ $movement->date }} </td>
				<td>{{ $movement->value }} </td>
				<td>{{ $movement->type }}</td>
				<td>{{ $movement->end_balance }}</td>
        </tr>
    @endforeach
    </tbody>
@endif

@endsection