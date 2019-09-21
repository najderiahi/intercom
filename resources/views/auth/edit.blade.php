@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                @include('auth.edit-menu')
            </div>
            <div class="col-md-8">
                <div class="card py-5 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="container">
                            <form action="{{ route('users.update', $user) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method("PATCH")

                                <div class="form-group row">
                                    <label for="name"
                                           class="col-md-4 col-form-label text-muted">{{ __('Avatar') }}</label>
                                    <div class="col-md-6">
                                        <file-uploader old="{{ $user->avatar_url }}" avatar="{{ $user->avatar }}"></file-uploader>
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
                                               name="first_name" value="{{ old('first_name', $user->first_name) }}"
                                               required
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
                                               name="last_name" value="{{ old('last_name', $user->last_name)  }}"
                                               required
                                               autocomplete="last_name" autofocus>

                                        @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary">Modifier</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

