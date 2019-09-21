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
                            <form action="{{ route('users.update.password', $user) }}" method="post">
                                @csrf
                                @method("PATCH")
                                <div class="form-group row">
                                    <label for="old_password"
                                           class="col-md-4 col-form-label text-muted">{{ __('Old Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="old_password" type="password"
                                               class="form-control @error('old_password') is-invalid @enderror"
                                               name="old_password" required autocomplete="new-password">

                                        @error('old_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="new_password"
                                           class="col-md-4 col-form-label text-muted">{{ __('Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="new_password" type="password"
                                               class="form-control @error('new_password') is-invalid @enderror"
                                               name="new_password" required autocomplete="new-password">

                                        @error('new_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="new_password-confirm"
                                           class="col-md-4 col-form-label text-muted">{{ __('Confirm Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="new_password-confirm" type="password" class="form-control"
                                               name="new_password_confirmation" required
                                               autocomplete="new-password">
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

