@extends('layouts.app') @section('content') @if(count($users))
<div class="container">
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Imagem</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            <h1>List of users</h1>

            @foreach ($users as $user)
            <tr>
                <td>
                    <img src="/storage/app/profiles/{{ $user->profile_photo }}">
                </td>
                <td>{{ $user->name }} </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif @endsection('content')