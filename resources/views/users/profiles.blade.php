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
                <img src="{{ asset('/storage/profiles/' . $user->profile_photo) }}" style='border-radius: 3px; width: 125px;'>
                </td>
                <td>{{ $user->name }} </td>
                @if ($associates->where('id', $user->id)->isNotEmpty())
                <td><strong>Associated</strong></td>
                @elseif ($associatesOf->where('id', $user->id)->isNotEmpty())
                <td><strong>Associated-of</strong></td>
                @else
                <td>Not associated</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif @endsection('content')