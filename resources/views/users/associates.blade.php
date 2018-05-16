@extends('layouts.app') @section('content') @if(count($users))
<div class="container">
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <h1>{{ $pagetitle }}</h1>

            @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }} </td>
                <td>{{ $user->email }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="container">
        <strong style="font-size:25px">You do not have any associate members!</strong>
    </div>
</div>
@endif @endsection('content')