<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="row">
            @if($user->avatar)
                <img src="{{$user->avatar_url}}" alt="" class="avatar-img large-avatar">
            @else
                <div
                    class="avatar-img large-avatar bg-light-success d-flex justify-content-center align-items-center text-white large-text">
                    {{ $user->first_name[0].$user->last_name[0] }}
                </div>
            @endif

            <div class="mx-3">
                <a href="{{ route("users.show", $user) }}"><h5 class="m-0">{{ $user->first_name." ".$user->last_name }}</h5></a>
                <span class="text-muted small"><i class="far fa-envelope"></i> {{ $user->email }}</span>
            </div>
        </div>
        <ul class="my-5 list-group">
            <li class="list-group-item my-3 border-0 @if(Route::is("users.edit")) bg-light-primary rounded @endif"><a href="{{ route("users.edit", $user) }}">Modifier mes informations</a>
            </li>
            <li class="list-group-item my-3 border-0 @if(Route::is("users.edit.password")) bg-light-primary rounded @endif"><a href="{{ route("users.edit.password", $user) }}">Modifier mon mot de passe</a></li>
        </ul>
    </div>
</div>
