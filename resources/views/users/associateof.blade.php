<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
    crossorigin="anonymous"> @extends('layouts.app') @section('content') @if(count($users))
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
                <td>
                    <a class="btn btn-xs btn-primary" href="{{ action('AccountController@accountsForUser',$user->id) }}">
                        <i class="fas fa-money-check-alt"></i>  Account</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="container">
        <strong style="font-size:25px">I do not belong to any group of associate members!</strong>
    </div>
</div>
@endif @endsection('content')