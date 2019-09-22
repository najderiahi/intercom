@extends('layouts.app')

@section('content')
    <div id="conversations" class="container" data-base="{{ route('conversations.index', [], false) }}">
        <conversations-component :user="{{ Auth::id() }}"></conversations-component>
    </div>
@stop
