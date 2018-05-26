@extends('layouts.app') 
@section('content')

<div class="container">

<h1>{{ $pagetitle }}</h1>

<div class="search">
    <form action="{{ route('profiles') }}" method="GET">
        <input type="text" class="searchTerm" placeholder="Search for name?" id="search" name="name">
            <button type="submit" class="searchButton">
                <i class="fa fa-search"></i> Search
            </button>
    </form>
</div>

@if(count($users))
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>Is Associated??</th>
            </tr>
        </thead>
        <tbody>
            <br>

            @foreach ($users as $user)
            @if($user->id != Auth::user()->id)
            <tr>
                <td>
                    <img src="{{ asset('/storage/profiles/' . $user->profile_photo) }}" style='border-radius: 3px; width: 125px;'>
                </td>
                <td>{{ $user->name }} </td>
                
                @if ($associates->where('id', $user->id)->isNotEmpty())
                <td>
                    <strong>Associated</strong>
                </td>
                @elseif ($associatesOf->where('id', $user->id)->isNotEmpty())
                <td>
                    <strong>Associated-of</strong>
                </td>
                @else
                <td>Not associated</td>
                @endif
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
    {{$users ->links()}}
</div>
@else
<p></p>
<h1>Your search found no matches!</h1>
@endif
@endsection('content')