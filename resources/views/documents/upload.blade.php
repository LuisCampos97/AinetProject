@extends('layouts.app') @section('content')
<form action="{{ route('uploadDocument', $movement->id) }}" method="post" class="form-group">
    {{ csrf_field() }}
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $pagetitle }}</div>
                    <div class="card-body">
                        @csrf

                        <div class="form-group row">
                            <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Document') }}</label>
                            <div class="col-md-6">
                                <input id="document_file" type="file" class="form-control{{ $errors->has('document_file') ? ' is-invalid' : '' }}" name="document_file" required> 
                                     @if ($errors->has('document_file'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('document_file') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="document_file" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>
                            <div class="col-md-6">
                                <input id="document_file" type="text" class="form-control{{ $errors->has('document_file') ? ' is-invalid' : '' }}" name="document_file"
                                    optional> @if ($errors->has('document_file'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('document_file') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Add Document') }}
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