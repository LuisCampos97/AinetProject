@extends('layouts.app') @section('content') @if(count($users))
<div class="container">
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>Is Associated??</th>
            </tr>
        </thead>
        <tbody>
            <h1>{{ $pagetitle }}</h1>

            <form action="{{ route('profiles') }}" method="get" class="form-inline">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Name">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>


            @foreach ($users as $user)
            <tr>
                <td>
                <img src="{{ asset('/storage/profiles/' . $user->profile_photo) }}">
                </td>
                <td>{{ $user->name }} </td>
                @foreach ($associates as $associate) 
                @if (Auth::user()->id == $user->associated_user_id)
                <td>
                    <strong>Associate-of</strong>
                </td>
                @elseif ($associate->associated_user_id == $user->id)
                <td>
                    <strong>Associate</strong>
                </td>
                @else
                <td>Not Associate</td>
                @endif 
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif @endsection('content')