@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    You are logged in!
                    
                </div>

            </div>

            <div class="card">
                <div class="card-body">
                    <a class="navbar-brand" href="http://ainetproject.ainet/users">List of registered users</a>g
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
