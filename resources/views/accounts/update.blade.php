@extends('layouts.app') @section('content')
<form action="{{ action('AccountController@updateAccount', $account->id) }}" method="post">
    @method('put') @csrf
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Update') }}</div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Type') }}</label>
                            <div class="col-md-6">
                                <select class="custom-select{{ $errors->has('account_type_id') ? ' is-invalid' : '' }}" id="type" name="account_type_id" class="form-control">
                                    <option disabled> -- Select an option -- </option>
                                    @foreach($accountType as $type)
                                    <option value="{{ $type->id }}" {{ old( 'type', strval($account->account_type_id)) == $type->id ? "selected" : "" }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('account_type_id'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('account_type_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('Code') }}</label>

                            <div class="col-md-6">
                                <input id="code" type="text" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code" value="{{ $account->code }}"
                                    required> @if ($errors->has('code'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('code') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="date" class="col-md-4 col-form-label text-md-right">{{ __('Date') }}</label>

                            <div class="col-md-6">
                                <input id="date" type="date" class="form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" name="code" value="{{ $account->date }}"
                                    required> @if ($errors->has('date'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('date') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="start_balance" class="col-md-4 col-form-label text-md-right">{{ __('Start Balance') }}</label>

                            <div class="col-md-6">
                                <input id="start_balance" type="text" class="form-control{{ $errors->has('start_balance') ? ' is-invalid' : '' }}" name="start_balance"
                                    value="{{ $account->start_balance }}"> @if ($errors->has('start_balance'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('start_balance') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                            <div class="col-md-6">
                                <input id="description" type="text" class="form-control{{ $errors->has('start_balance') ? ' is-invalid' : '' }}" name="description"
                                    value="{{ $account->description }}" optional> @if ($errors->has('description'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection