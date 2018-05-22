<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
@extends('layouts.app') @section('content')
<script type="text/javascript">
        $(document).ready(function(){
            $("select").change(function(){
                var str=$(this).val();
                if(str=='revenue')
                {
                    var start_balance="+";
                }
                else if(str=='expense')
                {
                    var start_balance="-";
                }
                $(".signal").html(start_balance);
            });
        });
    </script>
    
<form action="{{ route('storeMovement', $accounts[0]->id) }}" method="post" class="form-group">
{{ csrf_field() }}
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Create Movement') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('storeMovement', $accounts[0]->id) }}">
                            @csrf
                            <div class="form-group row">
                                <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Type') }}</label>
                                <div class="col-md-6">
                                    <select class="custom-select" id="type" name="type" class="form-control">
                                        <option disabled selected> -- Select an option -- </option>
                                        @foreach ($movementType as $type)
                                        <option value="{{ $type->type }}">{{ ucfirst($type->type) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('Category') }}</label>

                                <div class="col-md-6">
                                <select class="custom-select" id="category" name="category" class="form-control">
                                        <option disabled selected> -- Select an option -- </option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ ucfirst( $category->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="date" class="col-md-4 col-form-label text-md-right">{{ __('Date') }}</label>

                                <div class="col-md-6">
                                    <input id="date" type="date" class="form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" name="date" required>
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
                                    <input id="start_balance" type="number" class="form-control{{ $errors->has('value') ? ' is-invalid' : '' }}" name="value">
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
                                    <input id="description" type="text" class="form-control" name="description" optional>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Create') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
</form>
@endsection