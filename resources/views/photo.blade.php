@extends('layout.app')

@section('title', 'Upload your photo')

@section('content')

    <div id="app">
        <head-component></head-component>
        <div class="container">
            <router-view></router-view>
        </div>
        <foot-component></foot-component>
    </div>

@endsection