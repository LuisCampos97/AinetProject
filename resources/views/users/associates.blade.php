@extends('layouts.app') @section('content') @if(count($users))
<div class="container">
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <h1>{{ $pagetitle }}</h1>
            <a class="btn btn-xs btn-success" href="{{ action('UserController@addAssociate') }}">Add Associate</a></div>
            <br><br>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }} </td>
                <td>{{ $user->email }}</td>
                <td>
                    <form action="{{ action('UserController@deleteAssociate', $user->id) }}" method="post" class="inline">
                        @csrf @method('delete')
                        <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                    </form>
                </td>
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