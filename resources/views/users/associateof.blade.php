@extends('layouts.app') @section('content') @if(count($users))
<div class="container">
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Account Link</th>
            </tr>
        </thead>
        <tbody>
            <h1>{{ $pagetitle }}</h1>

            @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }} </td>
                <td>{{ $user->email }}</td>
                <td><a class="btn btn-xs btn-primary" href="{{ action('UserController@accountsForUser',$user->id) }}">Account</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif @endsection('content')