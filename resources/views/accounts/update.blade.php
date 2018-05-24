@extends('layouts.app') @section('content')
<form action="{{ action('AccountController@updateAccount') }}" method="post" class="form-group">
    {{ csrf_field() }}
    @method('put')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Update Account') }}</div>
                    <div class="card-body">

                        <form method="post" action="{{ route('updateAccount') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Type') }}</label>
                                <div class="col-md-6">
                                    <select class="custom-select" id="type" name="account_type_id" class="form-control">
                                        <option disabled> -- Select an option -- </option>
                                            <option value="{{ $account[0]->account_type_id }}">{{ $account[0]->name }}</option>
                                            @foreach ($accountType as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('Code') }}</label>

                                <div class="col-md-6">
                                    <input id="code" type="text" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code" value="{{ $account[0]->code }}" required> @if ($errors->has('code'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="start_balance" class="col-md-4 col-form-label text-md-right">{{ __('Start Balance') }}</label>

                                <div class="col-md-6">
                                    <input id="start_balance" type="number" class="form-control" name="start_balance" value="{{ $account[0]->start_balance }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                                <div class="col-md-6">
                                    <input id="description" type="text" class="form-control" name="description" value="{{ $account[0]->description }}" optional>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Update') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
</form>
@endsection