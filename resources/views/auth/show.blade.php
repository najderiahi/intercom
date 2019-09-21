@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="d-flex">
                        @if(!is_null($user->avatar))
                            <img src="{{ $user->avatar_url }}" alt="" class="avatar-img large-avatar">
                        @else
                            <div
                                class="avatar-img large-avatar bg-light-success d-flex justify-content-center align-items-center text-white large-text">
                                {{ $user->first_name[0].$user->last_name[0] }}
                            </div>
                        @endif
                        <div class="mx-3">
                            <h4 class="m-0">{{ $user->first_name." ".$user->last_name }}
                                @if(Auth::user()->id === $user->id)
                                <span><a href="{{ route('users.edit', $user->id) }}"><i class="fas fa-pen"></i></a></span>
                                @endif
                            </h4>
                            <span class="text-muted small"><i class="far fa-envelope"></i> {{ $user->email }}</span>
                        </div>
                    </div>
                    <div>
                        @if($user->active)
                            <span class="badge badge-success">Actif</span>
                        @else
                            <span class="badge badge-danger">Inactif</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm my-2">
        </div>
    </div>
@stop
