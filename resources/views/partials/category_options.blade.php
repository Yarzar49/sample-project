@extends('layouts.app')

@section('title')
    Product Registration
@endsection

@section('header')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
<style>
    
</style>
@endsection

@section('content')

<option value="">{{ __('messages.Category Name') }}</option>
@foreach ($categories as $category)
    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
@endforeach