@extends('layouts.app') @section('content')

<div class="container">
<table class="table table-bordered">
	<thead class="thead-dark">
		<tr>
			<th>Category</th>
			<th>Date</th>
			<th>Value</th>
			<th>Type</th>
            <th>End Balance</th>
		</tr>

		<h1>{{ $pagetitle }}</h1>
	</thead>
    <tbody>
    @foreach($movements as $movement)
		<tr>
				<td>{{ $movement->name }}</td>
				<td>{{ $movement->date }} </td>
				<td>{{ $movement->value }} </td>
				<td>{{ $movement->type }}</td>
				<td>{{ $movement->end_balance }}</td>
        </tr>
    @endforeach
    </tbody>
@endsection