@extends('layouts.app') @section('content')
<form action="{{ route('associate.store') }}" method="post" class="form-group">
    {{ csrf_field() }}
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Add Associate') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('associate.add') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('User to add') }}</label>
                                <div class="col-md-6">
                                    <select class="custom-select" id="type" name="account_type_id" class="form-control">
                                        <option disabled selected> -- Select an user -- </option>
                                        @foreach ($associates as $associate)
                                        @if ($associate->id != Auth::user()->id)
                                        <option value="{{ $associate->associated_user_id }}">{{ $associate->id }} - {{$associate->name }}</option>
                                        
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Add') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
</form>
@endsection