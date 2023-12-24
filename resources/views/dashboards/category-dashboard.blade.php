@extends('layouts.app')

@section('title')
    Category Dashboard
@endsection

@section('header')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/category-dashboard.css">
    <style>
        a {
            text-decoration: none;
        }

        .reachCategory {
            border-bottom: 2px solid #007bff;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col">
                <a href="{{ route('items.show') }}"><svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em"
                        fill="white" viewBox="0 0 448 512">
                        <path
                            d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" />
                    </svg></a>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-5">
        <div class="row flex-column flex-lg-row">
            <h2 class="h6 text-white-50">QUICK STATS</h2>
            @foreach ($results as $result)
                <div class="col-lg-3">
                    <a href=" {{ route('items.items-category', ['id' => $result->id]) }} ">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h3 class="card-title h2">{{ $result->item_count }} items</h3>
                                <span class="text-success">

                                    <i class="fas fa-chart-line"></i>
                                    <span class="fs-4">{{ $result->category_name }}</span>

                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col">
                <div class="d-flex justify-content-center">
                    <canvas id="categoryChart" style="max-width: 500px; max-height: 500px;"></canvas>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1"></script>
        <script>
            $(document).ready(function() {
                var categoryData = {!! json_encode($results) !!};
                var categoryNames = categoryData.map(function(item) {
                    return item.category_name;
                });
                var itemCounts = categoryData.map(function(item) {
                    return item.item_count;
                });

                var ctx = document.getElementById('categoryChart').getContext('2d');
                var categoryChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: categoryNames,
                        datasets: [{
                            data: itemCounts,
                            backgroundColor: [
                                '#FF6384',
                                '#36A2EB',
                                '#FFCE56',
                                '#8BC34A',
                                '#FF9800',
                                '#9C27B0',
                                '#009688'
                            ],
                            hoverBackgroundColor: [
                                '#FF6384',
                                '#36A2EB',
                                '#FFCE56',
                                '#8BC34A',
                                '#FF9800',
                                '#9C27B0',
                                '#009688'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: 10
                        }
                    }
                });
            });
        </script>
    @endsection
