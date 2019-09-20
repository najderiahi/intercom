@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="offset-md-1 col-md-10">
                <users-table :current-user="{{ Auth::id() }}"></users-table>
            </div>
        </div>
    </div>
@stop
