@extends('layouts.app') @section('content')

<form action="{{ route('storeMovement', $account->id) }}" method="post" class="form-group" enctype="multipart/form-data">
    @csrf
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Create Movement') }}</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label for="movement_category_id" class="col-md-4 col-form-label text-md-right">{{ __('Category') }}</label>
                            <div class="col-md-6">
                                <select class="custom-select {{ $errors->has('movement_category_id') ? ' is-invalid' : '' }}" id="movement_category_id" name="movement_category_id" class="form-control">
                                    <option disabled selected> -- Select an option -- </option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name.' ---- ('. $category->type.')'}} </option>
                                    @endforeach @if ($errors->has('movement_category_id'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('movement_category_id') }}</strong>
                                    </span>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="date" class="col-md-4 col-form-label text-md-right">{{ __('Date') }}</label>

                            <div class="col-md-6">
                                <input id="date" type="date" class="form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" name="date" required> @if ($errors->has('date'))
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
                                <input id="start_balance" type="text" class="form-control{{ $errors->has('value') ? ' is-invalid' : '' }}" name="value"> @if ($errors->has('value'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('value') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                            <div class="col-md-6">
                                <input id="description" type="text" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description"
                                    optional> @if ($errors->has('description'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <br>

                        <div class="form-group row">
                            <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Document File') }}</label>
                            <div class="col-md-6">
                                <input id="document_file" type="file" class="form-control{{ $errors->has('document_file') ? ' is-invalid' : '' }}" name="document_file" optional> @if ($errors->has('document_file'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('document_file') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="document_file" class="col-md-4 col-form-label text-md-right">{{ __('Document Description') }}</label>
                            <div class="col-md-6">
                                <input id="document_description" type="text" class="form-control{{ $errors->has('document_description') ? ' is-invalid' : '' }}"
                                    name="document_description" optional> @if ($errors->has('document_description'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('document_description') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create') }}
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