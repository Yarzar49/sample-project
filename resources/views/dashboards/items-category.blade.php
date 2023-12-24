@extends('layouts.app')

@section('title')
    Items table
@endsection

@section('header')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/itemList.css">
    <style>

    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col">
                <a href="{{ route('items.category-dashboard') }}" class=""><svg xmlns="http://www.w3.org/2000/svg" width="1.5em"
                        height="1.5em" fill="white" viewBox="0 0 448 512">
                        <path
                            d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row d-flex justify-content-center vh-100">
            <div class="col-md-12 mt-2">
                <div>
                    <a href="#" class="btn text-white mt-5 fs-5"
                        style="background:#009688;">{{ __('messages.Category Name') }} : {{ $categoryName }}</a>
                </div>
                <div class="table-responsive mt-2">
                    <table class="table mt-2 table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="table-header text-light">{{ __('messages.No') }}</th>
                                <th scope="col" class="table-header text-light">{{ __('messages.Item ID') }}
                                <th scope="col" class="table-header text-light">{{ __('messages.Item Code') }}
                                </th>
                                <th scope="col" class="table-header text-light">{{ __('messages.Item Name') }}
                                </th>
                                <th scope="col" class="table-header text-light">
                                    {{ __('messages.Category Name') }}
                                </th>
                                <th scope="col" class="table-header text-light">
                                    {{ __('messages.Safety Stock') }}
                                </th>
                                <th scope="col" class="table-header text-light">
                                    {{ __('messages.Received Date') }}
                                </th>

                            </tr>
                        </thead>
                        <tbody class="customtable">
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}
                                    </td>
                                    <td>{{ $item->item_id }}</td>
                                    <td>{{ $item->item_code }}</td>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->category_name }}</td>
                                    <td>{{ $item->safety_stock }}</td>
                                    <td>{{ $item->received_date }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
    <div class="pagination-container mt-2 mb-1">
        {{ $items->withQueryString()->links() }}
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
@endsection
