@if (isset($items) && $items->count() == 0 && $items->currentPage() > 1)
    @php
        // Get the current URL
        $currentUrl = url()->current();
        
        // Get the URL parameters as an associative array
        $queryParams = request()->query();
        
        // Set the previous page value
        $previousPage = $queryParams['page'];
        
        // Check if the previous page value exceeds the total number of available pages
        $maxPage = $items->lastPage();
        if ($previousPage > $maxPage) {
            $previousPage = $maxPage;
        }
        
        // Set the updated page value in the query parameters
        $queryParams['page'] = $previousPage;
        
        // Generate the previous page URL with the updated query parameters
        $previousPageUrl = $currentUrl . '?' . http_build_query($queryParams);
        
        // Redirect to the previous page URL
        header('Location: ' . $previousPageUrl);
        exit();
    @endphp
@endif

@extends('layouts.app')

@section('title')
    Items List
@endsection

@section('header')
    <link href="https://cdn.tailwindcss.com/2.2.19/tailwind.min.css" rel="stylesheet">
    {{-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> --}}

    <!--css file for this blade-->
    <link rel="stylesheet" href="/css/itemList.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <style>
        .btn {
            border: none;
        }

        .disabledForEdit {
            pointer-events: none;
            filter: blur(0.7px)
        }

        .disabledForDelete {
            filter: blur(0.5px)
        }

        button.btn:hover {
            background-color: #212529;
        }

        .edit-hover:hover {
            background-color: #212529;
        }

        .detail-hover:hover {
            background-color: #212529;
        }

        .reachList {
            border-bottom: 2px solid #007bff;
        }
    </style>
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col">
                <a href="{{ url()->previous() }}"><svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em"
                        fill="white" viewBox="0 0 448 512">
                        <path
                            d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" />
                    </svg></a>
            </div>
        </div>
    </div>
    <!-- Excel import Suceessful messages -->
    <div class="mt-2">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                {{ session('error') }}
            </div>
        @endif
    </div>

    <div class="back">
        <!-- Search bar -->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('items.search') }}" method="GET" class="row g-3 mt-3" id="search-form">
                        @csrf
                        <div class="col-auto">
                            <input type="text" name="item_id" id="item_id" class="form-control"
                                placeholder="{{ __('messages.Item ID') }}" autocomplete="off">
                        </div>
                        <div class="col-auto">
                            <input type="text" name="item_code" id="item_code" class="form-control"
                                placeholder="{{ __('messages.Item Code') }}" autocomplete="off">
                        </div>
                        <div class="col-auto">
                            <input type="text" name="item_name" id="item_name" class="form-control"
                                placeholder="{{ __('messages.Item Name') }}" autocomplete="off">
                        </div>
                        <div class="col-auto">
                            <select name="category_name" class="form-select">
                                <option value="">{{ __('messages.Category Name') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->category_name }}">{{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" style="background-color: #009688;"
                                class="btn text-white search">{{ __('messages.Search') }}</button>
                        </div>

                        @if ($items->count() > 0)
                            <div class="ms-auto text-center mt-4">
                                <a href="{{ route('items.download-pdf', array_merge(Request::query(), ['page' => $items->currentPage()])) }}"
                                    class="btn btn-sm btn-secondary me-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                        <path
                                            d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384V304H176c-35.3 0-64 28.7-64 64V512H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zM176 352h32c30.9 0 56 25.1 56 56s-25.1 56-56 56H192v32c0 8.8-7.2 16-16 16s-16-7.2-16-16V448 368c0-8.8 7.2-16 16-16zm32 80c13.3 0 24-10.7 24-24s-10.7-24-24-24H192v48h16zm96-80h32c26.5 0 48 21.5 48 48v64c0 26.5-21.5 48-48 48H304c-8.8 0-16-7.2-16-16V368c0-8.8 7.2-16 16-16zm32 128c8.8 0 16-7.2 16-16V400c0-8.8-7.2-16-16-16H320v96h16zm80-112c0-8.8 7.2-16 16-16h48c8.8 0 16 7.2 16 16s-7.2 16-16 16H448v32h32c8.8 0 16 7.2 16 16s-7.2 16-16 16H448v48c0 8.8-7.2 16-16 16s-16-7.2-16-16V432 368z" />
                                    </svg>
                                    PDF Download
                                </a>

                                <a href="{{ route('items.download-excel', array_merge(Request::query(), ['page' => $items->currentPage()])) }}"
                                    class="btn btn-sm btn-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512">
                                        <path
                                            d="M64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V160H256c-17.7 0-32-14.3-32-32V0H64zM256 0V128H384L256 0zM155.7 250.2L192 302.1l36.3-51.9c7.6-10.9 22.6-13.5 33.4-5.9s13.5 22.6 5.9 33.4L221.3 344l46.4 66.2c7.6 10.9 5 25.8-5.9 33.4s-25.8 5-33.4-5.9L192 385.8l-36.3 51.9c-7.6 10.9-22.6 13.5-33.4 5.9s-13.5-22.6-5.9-33.4L162.7 344l-46.4-66.2c-7.6-10.9-5-25.8 5.9-33.4s25.8-5 33.4 5.9z" />
                                    </svg>
                                    Excel Download
                                </a>
                            </div>
                        @endif
                    </form>

                </div>
            </div>
        </div>

        <!-- Items List -->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if ($items->count() > 0)
                        <div class="row-count d-flex justify-content-end">
                            <h5><span class="badge bg-secondary">{{ __('messages.Total') }} {{ $items->total() }}
                                    row(s)</span></h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table mt-2 table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col" class="table-header text-light">{{ __('messages.No') }}</th>
                                        <th scope="col" class="table-header text-light">{{ __('messages.Item ID') }}
                                        </th>
                                        <th scope="col" class="table-header text-light">{{ __('messages.Item Code') }}
                                        </th>
                                        <th scope="col" class="table-header text-light">{{ __('messages.Item Name') }}
                                        </th>
                                        <th scope="col" class="table-header text-light">
                                            {{ __('messages.Category Name') }}</th>
                                        <th scope="col" class="table-header text-light">
                                            {{ __('messages.Safety Stock') }}</th>
                                        <th class="text-center text-light" scope="col">
                                            {{ __('messages.Action') }}<br>
                                            <hr class="text-light">
                                            <div class="row">
                                                <div class="col text-center text-light">{{ __('messages.Items Edit') }}
                                                </div>
                                                <div class="col text-center text-light">{{ __('messages.Detail') }}</div>
                                                <div class="col text-center text-light">
                                                    {{ __('messages.Active') }}/<br>{{ __('messages.Inactive') }}</div>
                                                <div class="col text-center text-light">{{ __('messages.Remove') }}</div>
                                            </div>
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
                                            <td>

                                                <div class="row">
                                                    <div class="col text-center">
                                                        <a href=" {{ route('items.edit', ['id' => $item->id]) }}"
                                                            class="btn edit-hover {{ $item->deleted_at ? 'disabledForEdit' : '' }}"
                                                            id="edit-btn-{{ $item->id }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                                                fill="#ff9800" viewBox="0 0 512 512">
                                                                <path
                                                                    d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                    <div class="col text-center">
                                                        <a href="{{ route('items.show-detail', ['id' => $item->id]) }} "
                                                            class="btn detail-hover">
                                                            <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                                                fill="#007bff" viewBox="0 0 512 512">
                                                                <path
                                                                    d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                    <div class="col text-center">
                                                        @if ($item->deleted_at == null)
                                                            <button type="submit"
                                                                class="btn btn-sm text-white btn-success active-button"
                                                                id="activeButton{{ $item->id }}"
                                                                data-bs-toggle="modal" data-bs-target="#inactiveModal"
                                                                value="{{ $item->id }}">Active</button>
                                                        @else
                                                            <button type="submit"
                                                                class="btn btn-sm text-white btn-danger inactive-button"
                                                                id="inactiveButton{{ $item->id }}"
                                                                data-bs-toggle="modal" data-bs-target="#activeModal"
                                                                value="{{ $item->id }}">Inactive</button>
                                                        @endif
                                                        <!-- <a href="" class="btn btn-sm text-white " style="background-color: #009688;">Active</a> -->
                                                    </div>
                                                    <div class="col text-center">
                                                        <button type="submit" data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal"
                                                            class="btn deleteBtn {{ $item->deleted_at ? 'disabledForDelete' : '' }}"
                                                            value="{{ $item->id }}"
                                                            {{ $item->deleted_at ? 'disabled' : '' }}>
                                                            <svg id="mySvg" xmlns="http://www.w3.org/2000/svg"
                                                                height="1em" fill="red" viewBox="0 0 448 512">
                                                                <path
                                                                    d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z" />
                                                            </svg>
                                                        </button>


                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Active button Modal -->
                        <div class="modal fade" id="inactiveModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3>Inactive Confrimation</h3>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{ __('messages.Confirm Inactive') }}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary"
                                            id="changeInactive">{{ __('messages.Open Inactive') }}</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">{{ __('messages.Cancel') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Inactive button Modal -->
                        <div class="modal fade" id="activeModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3>Active Confrimation</h3>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{ __('messages.Confirm Active') }}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" id="changeActive"
                                            class="btn btn-primary">{{ __('messages.Open Active') }}</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">{{ __('messages.Cancel') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete button Modal -->
                        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <input type="hidden" id="itemCount" value="{{ $items->count() }}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3>Delete Confrimation</h3>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{ __('messages.Confirm Delete') }}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" id="changeDelete"
                                            class="btn btn-primary">{{ __('messages.Delete') }}</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">{{ __('messages.Cancel') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- tailwind pagination -->
                        <div class="pagination-container mt-2 mb-1" id="pagination">
                            {{ $items->withQueryString()->links() }}
                        </div>
                    @else
                        <h4 class="text-light d-flex justify-content-center mt-5">{{ __('messages.No Items') }}</h4>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {

            // Initialize autocomplete for item_id field
            $("#item_id").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('autocomplete.item_id') }}",
                        method: 'GET',
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                minLength: 1,
                select: function(event, ui) {
                    // When an item is selected from the autocomplete list, populate the input fields
                    $('#item_id').val(ui.item.value);

                    // Fetch the item details for the selected item_id
                    $.ajax({
                        url: "{{ route('items.fetch') }}",
                        method: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "item_id": ui.item.value
                        },
                        success: function(response) {
                            if (response.success) {
                                // Populate the item name and item code input fields
                                $('#item_name').val(response.data.item_name);
                                $('#item_code').val(response.data.item_code);
                                $('#search-form').submit(); // Submit the form
                            } else {
                                // Clear the input fields if item ID is not found
                                $('#item_name').val('');
                                $('#item_code').val('');
                            }
                        },
                        error: function() {
                            // Handle any errors that occur during the AJAX request
                        }
                    });
                    return false; // Prevent the default select behavior
                }
            });

            // Initialize autocomplete for item_code field
            $("#item_code").autocomplete({
                source: "{{ route('autocomplete.item_code') }}",
                minLength: 1,
                select: function(event, ui) {
                    // When an item is selected from the autocomplete list, populate the input field
                    $('#item_code').val(ui.item.value);
                    $('#search-form').submit(); // Submit the form
                }
            });

            // Initialize autocomplete for item_name field
            $("#item_name").autocomplete({
                source: "{{ route('autocomplete.item_name') }}",
                minLength: 1,
                select: function(event, ui) {
                    // When an item is selected from the autocomplete list, populate the input field
                    $('#item_name').val(ui.item.value);
                    $('#search-form').submit(); // Submit the form
                }
            });
        });
    </script>
    <script>
        // Get the URL parameters
        const urlParams = new URLSearchParams(window.location.search);

        // Get the value of parameters
        const itemId = urlParams.get('item_id');
        const itemCode = urlParams.get('item_code');
        const itemName = urlParams.get('item_name');


        // Fill the input field with the 'item_name' value
        document.getElementById('item_id').value = itemId;
        document.getElementById('item_code').value = itemCode;
        document.getElementById('item_name').value = itemName;
    </script>

    <script>
        $(document).ready(function() {

            //Auto fill with Item ID
            $('input[name="item_id"]').on('input', function() {
                var itemId = $(this).val();

                // Make an AJAX request to retrieve item details
                $.ajax({
                    url: "{{ route('items.fetch') }}",
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "item_id": itemId
                    },
                    success: function(response) {
                        if (response.success) {
                            // Populate the item name and item code input fields
                            $('#item_name').val(response.data.item_name);
                            $('#item_code').val(response.data.item_code);
                        } else {
                            // Clear the input fields if item ID is not found
                            $('#item_name').val('');
                            $('#item_code').val('');
                        }
                    },
                    error: function() {
                        // Handle any errors that occur during the AJAX request
                    }
                });
            });
        });

        $(document).ready(function() {
            $(".active-button").click(function() {
                var itemId = $(this).val(); // Get the value of the active button

                $("#changeInactive").val(itemId); // Set the value of the changeInactive button
                console.log($("#changeInactive").val());
            });


            // Event handler for the click event on the "Inactive" button inside the modal box
            $("#changeInactive").click(function() {
                var itemId = $(this).val();

                $.ajax({
                    url: " {{ route('items.toggle') }}",
                    method: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "itemId": itemId
                    },

                    success: function(response) {
                        console.log(response);
                        $("#activeButton" + itemId).text("Inactive");
                        $("#inactiveModal").modal("hide");
                        if (response.success) {
                            console.log(response.success);
                            // Item deleted successfully, show success message in Blade UI
                            $('.alert-success').text(response.success).show();
                        } else if (response.error) {
                            // Error occurred, show error message in Blade UI
                            console.log(response.error);
                            $('.alert-danger').text(response.error).show();
                        }
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.log("Error occurred:", error);
                    },
                });
            });
        });

        $(document).ready(function() {
            $(".inactive-button").click(function() {
                var itemId = $(this).val(); // Get the value of the active button
                console.log(itemId);
                $("#changeActive").val(itemId); // Set the value of the changeInactive button
            });
            // Event handler for the click event on the "Inactive" button inside the modal box
            $("#changeActive").click(function() {
                var itemId = $(this).val();
                console.log(itemId);

                $.ajax({
                    url: " {{ route('items.toggle-two') }}",
                    method: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "itemId": itemId
                    },

                    success: function(response) {
                        console.log(response);
                        // Enable the edit and delete buttons using CSS
                        if (response.success) {
                            console.log(response.success);
                            // Item deleted successfully, show success message in Blade UI
                            $('.alert-success').text(response.success).show();
                        } else if (response.error) {
                            // Error occurred, show error message in Blade UI
                            console.log(response.error);
                            $('.alert-danger').text(response.error).show();
                        }
                        $("#activeButton" + itemId).text("Active");
                        $("#activeModal").modal("hide");
                        location.reload();


                    },
                    error: function(xhr, status, error) {
                        console.log("Error occurred:", error);
                    },
                });
            });
        });

        $(document).ready(function() {
            $(".deleteBtn").click(function() {
                var itemId = $(this).val(); // Get the value of the active button

                $("#changeDelete").val(itemId); // Set the value of the changeInactive button

            });
            // Event handler for the click event on the "Inactive" button inside the modal box
            $("#changeDelete").click(function() {
                var itemId = $(this).val();
                var itemCount = $("#itemCount").val(); //Get Item Count
                console.log(itemCount);
                // Get the current page number from the URL
                const urlParams = new URLSearchParams(window.location.search);
                let currentPage = parseInt(urlParams.get("page")) || 1;

                var searchItemId = urlParams.get("itemId");
                var searchItemCode = urlParams.get("itemCode");
                var searchItemName = urlParams.get("itemName");
                var searchCategory = urlParams.get("category");

                $.ajax({
                    url: "/items/" + itemId,
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },

                    success: function(response) {
                        console.log(response.success);
                        if (response.success) {
                            // Item deleted successfully

                            // Item deleted successfully, show success message in Blade UI
                            $('.alert-success').text(response.success).show();
                        } else if (response.error) {
                            // Error occurred, show error message in Blade UI
                            console.log(response.error);
                            $('.alert-danger').text(response.error).show();
                        }
                        if (itemCount > 1) {

                            if (searchItemId || searchItemCode || searchItemName ||
                                searchCategory) {
                                // Redirect back to the search results
                                var redirectUrl = "/items/search?";

                                if (searchItemId) {
                                    redirectUrl += "itemId=" + searchItemId + "&";
                                }
                                if (searchItemCode) {
                                    redirectUrl += "itemCode=" + searchItemCode + "&";
                                }
                                if (searchItemName) {
                                    redirectUrl += "itemName=" + searchItemName + "&";
                                }
                                if (searchCategory) {
                                    redirectUrl += "category=" + searchCategory + "&";
                                }
                                redirectUrl += "page=" + currentPage;

                                window.location.href = redirectUrl;

                            } else {
                                window.location.href = "/items/show?page=" + currentPage;

                            }
                        } else {
                            if (searchItemId || searchItemCode || searchItemName ||
                                searchCategory) {
                                // Redirect back to the search results
                                var redirectUrl = "/items/search?";

                                if (searchItemId) {
                                    redirectUrl += "itemId=" + searchItemId + "&";
                                }
                                if (searchItemCode) {
                                    redirectUrl += "itemCode=" + searchItemCode + "&";
                                }
                                if (searchItemName) {
                                    redirectUrl += "itemName=" + searchItemName + "&";
                                }
                                if (searchCategory) {
                                    redirectUrl += "category=" + searchCategory + "&";
                                }
                                redirectUrl += "page=" + (currentPage - 1);

                                window.location.href = redirectUrl;

                            } else {
                                window.location.href = "/items/show?page=" + (currentPage - 1);
                            }
                        }



                    },
                    error: function(xhr, status, error) {
                        console.log("Error occurred:", error);
                    },
                });
            });
        });
    </script>
@endsection
