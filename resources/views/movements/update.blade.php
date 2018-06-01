@extends('layouts.app')
@section('content')
<form action="{{ action('MovementController@updateMovement', [$account->id, $movement->id]) }}" method="post">
	@method('put')
    @csrf
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Update') }}</div>
                    <div class="card-body">

                        <div class="form-group row">
                                <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('Category') }}</label>

                                <div class="col-md-6">
                                <select class="custom-select" id="category" name="category" class="form-control">
                                        <option disabled selected> -- Select an option -- </option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{  $category->name }}</option>
                                        @endforeach

                                        @if ($errors->has('category'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('category') }}</strong>
                                            </span>
                                        @endif
                        
                                    </select>
                                </div>
                            </div>


                    <div class="form-group row">
                                <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Type') }}</label>
                                <div class="col-md-6">
                                    <select class="custom-select" id="type" name="type" class="form-control">
                                        <option disabled selected> -- Select an option -- </option>
                                        @foreach ($movementType as $type)
                                        <option value="{{ $type->type }}">{{$type->type }}</option>
                                        @endforeach

                                        @if ($errors->has('type'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('type') }}</strong>
                                            </span>
                                        @endif

                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="date" class="col-md-4 col-form-label text-md-right">{{ __('Date') }}</label>

                                <div class="col-md-6">
                                    <input id="date" type="date" value="{{ $movement->date }}" class="form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" name="date" required>
                                    @if ($errors->has('date'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('date') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="value" class="col-md-4 col-form-label text-md-right">{{ __('Value') }}</label>
                                <a class="signal" style="font-size:30px"></a>
                                <div class="col-md-6">
                                    <input id="value" value="{{ $movement->value }}" type="number" class="form-control{{ $errors->has('value') ? ' is-invalid' : '' }}" name="value">
                                    @if ($errors->has('value'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('value') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                                <div class="col-md-6">
                                    <input id="description" value="{{ $movement->description }}" type="text" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" optional>
                                    @if ($errors->has('description'))
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