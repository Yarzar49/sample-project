@php
$currentUrl = url()->current();
$previousUrl = url()->previous();
if ($currentUrl != $previousUrl)
Session::put('requestReferrer', $previousUrl);
@endphp

@extends('layouts.app')

@section('title')
    Item Details
@endsection

@section('header')
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
    <link rel="stylesheet" href="/css/detail.css">
    <style>
        /* Nav bar shallow*/
        .navbar {
            box-shadow: none;
            border-bottom: 1px solid #ddd;
            background-color: #f5f5f5;
            box-shadow: 4px 6px 12px rgba(0, 0, 0, 0.4);
            position: sticky;
            top: 0;
            z-index: 100;
        }


        .navbar .nav-link {
            padding: 0.5rem 1rem;
            color: #333;
            transition: color 0.3s ease;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link:focus {
            color: #666;
        }

        .navbar.nav-link:hover {
            color: #FFFFFF;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Calibri', sans-serif !important;
            min-height: 100vh;
            /* background: linear-gradient(to bottom, #000428, #004683); */
            background: linear-gradient(to bottom, #000428, #004683);
        }

        .detailDisabled {
            pointer-events: none;
            background: #585755;
            padding: 1.2em 1.5em;
            border: none;
            text-transform: UPPERCASE;
            font-weight: bold;
            color: #fff;
            -webkit-transition: background 0.3s ease;
            transition: background 0.3s ease;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col">
                <a href="{{ Session::get('requestReferrer') }}"><svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em"
                        fill="white" viewBox="0 0 448 512">
                        <path
                            d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" />
                    </svg></a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="card">
            <div class="container-fliud">
                <div class="wrapper row">
                    <div class="preview col-md-6">
                        <div class="preview-pic tab-content">
                            <div class="tab-pane active" id="pic-1">
                                @if (isset($item) && is_object($item) && isset($item->file_path))
                                    <img class="rounded" src="{{ asset($item->file_path) }}" alt="" height="350px">
                                @else
                                    <img class="rounded"
                                        src="https://www.freeiconspng.com/thumbs/no-image-icon/no-image-icon-6.png"
                                        alt="No Image Available" height="350px">
                                @endif
                            </div>
                        </div>


                    </div>
                    <div class="details col-md-6">
                        <h3 class="product-title">{{ __('messages.Product Details') }}</h3>
                        <h5 class=""><span class="fw-bold">{{ __('messages.Item ID') }}</span> {{ $item->item_id }}</h5>
                        <h5 class=""><span class="fw-bold">{{ __('messages.Item Name') }}</span> {{ $item->item_name }}</h5>
                        <h5 class=""><span class="fw-bold">{{ __('messages.Item Code') }}</span> {{ $item->item_code }}</h5>
                        <h5 class=""><span class="fw-bold">{{ __('messages.Category Name') }}</span> {{ $item->category_name }}</h5>
                        <h5 class=""><span class="fw-bold">{{ __('messages.Safety Stock') }}</span> {{ $item->safety_stock }}</h5>
                        <h5 class=""><span class="fw-bold">{{ __('messages.Received Date') }} </span>{{ $item->received_date }}</h5>
                        <h5 class=""><span class="fw-bold">{{ __('messages.Description') }}</span>
                        <p>{{ $item->description }}</p></h5>
                        <div class="action">
                            @if ($item['deleted_at'] == null)
                                <a href="{{ route('items.edit', ['id' => $item->id]) }}"
                                    class="add-to-cart btn btn-default">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" fill="white"
                                        viewBox="0 0 512 512">
                                        <path
                                            d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z" />
                                    </svg>
                                    {{ __('messages.Items Edit') }}
                                </a>
                            @else
                                <a href="{{ route('items.edit', ['id' => $item->id]) }}"
                                    class="btn btn-default detailDisabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" fill="white"
                                        viewBox="0 0 512 512">
                                        <path
                                            d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z" />
                                    </svg>
                                    Edit
                                </a>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
@endsection
