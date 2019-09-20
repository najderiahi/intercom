@extends('layouts.app')

@section('content')
    <form action="{{ route('users.update') }}" method="post">
        @csrf
        @method("PATCH")

        <div class="form-group row">
            <label for="name"
                   class="col-md-4 col-form-label text-muted">{{ __('Avatar') }}</label>
            <div class="col-md-6">
                <file-uploader></file-uploader>
            </div>
            @error('avatar')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="form-group row">
            <label for="first_name"
                   class="col-md-4 col-form-label text-muted">{{ __('First Name') }}</label>

            <div class="col-md-6">
                <input id="first_name" type="text"
                       class="form-control @error('first_name') is-invalid @enderror"
                       name="first_name" value="{{ old('first_name') }}" required
                       autocomplete="first_name" autofocus>

                @error('first_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <label for="last_name"
                   class="col-md-4 col-form-label text-muted">{{ __('Last Name') }}</label>

            <div class="col-md-6">
                <input id="last_name" type="text"
                       class="form-control @error('last_name') is-invalid @enderror"
                       name="last_name" value="{{ old('last_name') }}" required
                       autocomplete="last_name" autofocus>

                @error('last_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
    </form>
@stop
