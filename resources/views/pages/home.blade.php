@extends('layout.master-page')

@section('title', 'Dashboard')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">General Dashboard - {{ str(auth()->user()->getRoleNames()[0])->title }}</h1>
@endsection
