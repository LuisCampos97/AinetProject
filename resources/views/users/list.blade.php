@extends('master')
@section('content')

@if(count($users))
<table class="table table-stripe">
<thead> <tr> <th>Name</th> <th>Email</th> <th>Created</th> <th>Type</th><th>Phone Number</th> </tr> </thead>
<tbody>
@foreach ($users as $user)
	<tr>
		<td>{{ $user->name }} </td>
		<td>{{ $user->email }} </td>
		<td>{{ $user->created_at }} </td>
		<td>{{ $user->typeToString() }} </td>
		<td>{{ $user->phone}}</td>		
	</tr>
@endforeach
</tbody>
</table>
@endif
@endsection('content')