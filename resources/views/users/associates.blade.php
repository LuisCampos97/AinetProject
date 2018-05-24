<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
    crossorigin="anonymous"> @extends('layouts.app') @section('content') @if(count($users))

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
            <a class="btn btn-xs btn-success" href="{{ action('ProfileController@addAssociate') }}">
                <i class="fas fa-user-plus"></i>  Add Associate</a>
            <br>
            <br> @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }} </td>
                <td>{{ $user->email }}</td>
                <td>
                    <form action="{{ action('ProfileController@deleteAssociate', $user->id) }}" method="post" class="inline">
                        @csrf @method('delete')
                        <button type="submit" class="btn btn-xs btn-danger">
                            <i class="fas fa-user-minus"></i>  Remove Associate</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="container">
        <strong style="font-size:25px">You do not have any associate members!</strong>
        <div>
            <a class="btn btn-xs btn-success" href="{{ action('ProfileController@addAssociate') }}">
                <i class="fas fa-user-plus"></i>Add Associate</a>
        </div>
    </div>
</div>
@endif @endsection('content')