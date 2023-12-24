@php
$currentUrl = url()->current();
$previousUrl = url()->previous();
if ($currentUrl != $previousUrl)
Session::put('requestReferrer', $previousUrl);
@endphp
@extends('layouts.app')

@section('title')
    Items Edit Form
@endsection

@section('header')
    
    <link rel="stylesheet" href="/css/register.css">
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

        .navbar-nav .nav-link {
            position: relative;
            overflow: hidden;
        }

        .navbar-nav .nav-link::before {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #007bff;
            transform: scaleX(0);
            transition: transform 0.3s;
        }

        .navbar-nav .nav-link:hover::before {
            transform: scaleX(1);
        }

        .beautiful-text {
            font-family: "Arial", sans-serif;
            font-size: 24px;
            font-weight: bold;
            color: #002147;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            animation: glowing 2s infinite;
        }

        @keyframes glowing {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
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

    <!-- Register Form -->
    <section>
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-lg-10">

                    <!-- Normal Register Form -->
                    <div class="wrapper" id="normalForm">
                        <form method="POST" action=" {{ route('items.update', $item->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="h5 font-weight-bold text-center mb-3">{{ __('messages.ItemsEditTitle') }}</div>
                            <div class="form-group d-flex align-items-center">
                                <div class="">
                                    <label for="itemID" class="form-label text-white">{{ __('messages.Item ID') }}</label>
                                    <input type="text" id="itemID" name="item_id" class="form-control"
                                        value="{{ $item->item_id }}" readonly>
                                </div>
                            </div>
                            @error('item_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-group d-flex align-items-center mt-5">
                                <div class="">
                                    <label for="itemCode" class="form-label text-white">{{ __('messages.Item Code') }}<span
                                            style="color:red;">*</span></label>
                                    <input type="text" id="itemCode" class="form-control" name="item_code" autocomplete="off"
                                        placeholder="Enter Item Code" value="{{ $item->item_code }}">
                                </div>
                            </div>
                            @error('item_code')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-group d-flex align-items-center mt-5">
                                <div class="">
                                    <label for="itemName" class="form-label text-white">{{ __('messages.Item Name') }}<span
                                            style="color:red;">*</span></label>
                                    <input type="text" id="itemName" class="form-control" name="item_name" autocomplete="off"
                                        placeholder="Enter Item Name" value="{{ $item->item_name }}">
                                </div>
                            </div>
                            @error('item_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-group d-flex align-items-center mt-5">
                                <div class="">
                                    <label for="safetyStock"
                                        class="form-label text-white">{{ __('messages.Safety Stock') }}<span
                                            style="color:red;">*</span></label>
                                    <input type="text" id="safetyStock" class="form-control" name="safety_stock" autocomplete="off"
                                        placeholder="Enter Safety Stock" value="{{ $item->safety_stock }}" maxlength="10">
                                </div>
                            </div>
                            @error('safety_stock')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <!-- <div class="d-flex align-items-center mt-3"> -->
                            <div class="row mt-5">
                                <label for="selectedBox"
                                    class="form-label text-white">{{ __('messages.Category Name') }}<span
                                        style="color:red;">*</span></label>
                                <div class="col-10">
                                    <select id="selectedBox" class="form-select" name="categoryName">
                                        <option value="">{{ __('messages.Category Name') }}</option>
                                        <!-- Fetch and populate categories from tbl_categories table -->
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $category->id == $item->category_id ? 'selected' : '' }}>
                                                {{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2">
                                    <div class="row">
                                        <div class="col-6">
                                            <button type="button" class="btn btn-success" id="addCategoryButton"
                                                data-bs-toggle="modal" data-bs-target="#dialogBox">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" fill="white"
                                                    viewBox="0 0 448 512">
                                                    <path
                                                        d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="col-4">
                                            <button type="button" class="btn btn-danger" id="removeCategoryButton"
                                                data-bs-toggle="modal" data-bs-target="#removeDialogBox">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" fill="white"
                                                    viewBox="0 0 448 512">
                                                    <path
                                                        d="M432 256c0 17.7-14.3 32-32 32L48 288c-17.7 0-32-14.3-32-32s14.3-32 32-32l352 0c17.7 0 32 14.3 32 32z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- </div> -->
                            @error('categoryName')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class=" d-flex align-items-center mt-5">
                                <div class="mb-3 col-md-12">
                                    <label for="receivedDate"
                                        class="form-label text-white">{{ __('messages.Received Date') }}<span
                                            style="color:red;">*</span></label>
                                    <input type="date" id="receivedDate" class="form-control" name="received_date" autocomplete="off"
                                        value="{{ $item->received_date }}">
                                </div>
                            </div>
                            @error('received_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-group mt-5">
                                <label for="description"
                                    class="form-label text-white">{{ __('messages.Description') }}</label>
                                <textarea id="description" class="form-control" name="description">{{ $item->description }}</textarea>
                            </div>
                            <label for="photo"
                                class="form-label text-white mt-5">{{ __('messages.Upload Photo') }}</label>
                            <div class="input-group">
                                <input type="file" id="photo" class="form-control fileInput" name="photo">
                                {{-- <input type="hidden" name="photo_id" id="photoId" value="{{ $item->id }}"> --}}
                                <input type="hidden" name="remove_image" id="removeImageFlag" value="">
                                <button type="button" id="removePhoto"
                                    class="btn btn-danger ms-2">{{ __('messages.Remove') }}</button>
                            </div>
                            @error('photo')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <!--Show chosen image from file input in UI  -->
                            <div class="mb-3 mt-3">
                                @if ($item->file_path)
                                    <img src="{{ asset($item->file_path) }}" id="previewImage" style="max-width: 200px;"
                                        alt="Item Image">
                                @else
                                    <img src="" id="previewImage" style="max-width: 200px;" alt="No Image">
                                @endif
                            </div>
                            <div class="mt-3 d-flex justify-content-center">
                                <button type="submit"
                                    class="btn btn-primary mb-3">{{ __('messages.Items Update') }}</button>
                            </div>

                        </form>
                    </div>

                    <!--Category Add Dialog-->
                    <div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" id="dialogBox"
                        aria-hidden="true" style="display: none;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                                        {{ __('messages.Category Registration') }}</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <label for="categoryName"
                                        class="form-label">{{ __('messages.Category Name') }}</label>
                                    <input type="text" id="dialogCategoryInput" class="form-control"
                                        name="categoryName" required>
                                    <div id="errorContainer" style="color: red;"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">{{ __('messages.Close') }}</button>
                                    <button type="button" id="dialogSaveButton"
                                        class="btn btn-primary">{{ __('messages.Save') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Category Remove Dialog-->
                    <div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" id="removeDialogBox"
                        aria-hidden="true" style="display: none;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                                        {{ __('messages.Category Remove') }}</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <label for="categoryName"
                                        class="form-label">{{ __('messages.Category Name') }}</label>
                                    <select id="removeDialogCategorySelect">
                                        <option value="">Select a category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                    <div id="errorRemoveContainer" style="color: red;"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">{{ __('messages.Close') }}</button>
                                    <button type="button" id="removeDialogButton"
                                        class="btn btn-primary">{{ __('messages.Remove') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- Create the Bootstrap modal for the alert -->
    <div class="modal fade" id="customAlertModal" tabindex="-1" aria-labelledby="customAlertTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customAlertTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="customAlertMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>



    <!-- JS Cutomize Alert-->
    <script src="/jquery/customAlert.js"></script>


    <!-- Category Add and Remove Dialog Handle-->
    <script>
        //When the page is reloaded, the register input fields are disappearing
        if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
            // Reload the page if navigating back
            location.reload(true);
        }


        $(document).ready(function() {

            //alert timeout
            setTimeout(function() {
                $(".text-danger").fadeOut();
            }, 6000);

            $('#addCategoryButton').click(function() {
                $('#dialogBox').modal('show');
                $('#dialogCategoryInput').val('');
                //Remove error message
                $('#errorContainer').text("");
            });

            // Save Category button click event
            $('#dialogSaveButton').click(function() {
                var categoryName = $('#dialogCategoryInput').val().trim().toUpperCase();
                if (categoryName === '') {
                    $('#errorContainer').text("Please enter a category name");
                    return true;
                } else {
                    $('#errorContainer').text("");
                }
                //Duplicate category name error
                var duplicateFound = false;
                var options = document.getElementById('selectedBox').options;

                for (var i = 0; i < options.length; i++) {
                    if (options[i].text === categoryName) {
                        duplicateFound = true;
                        break; // Exit the loop early if a duplicate is found
                    }
                }

                if (duplicateFound) {
                    $('#errorContainer').text("Duplicate category name.");
                    return true;
                } else {
                    $('#errorContainer').text("");
                }

                // Perform AJAX request to save the category            
                $.ajax({
                    url: " {{ route('categories.store') }}",
                    method: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "categoryName": categoryName
                    },

                    success: function(response) {
                        // Add the new category option to the select box
                        var option = $('<option>').text(categoryName);
                        option.attr('value', response);
                        $('#selectedBox').append(option);

                        // Hide the dialog box and clear the input
                        // $('#dialogBox').hide();

                        //Remove background backdrop
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();

                        // Re-enable scrolling
                        $('body').css('overflow', 'auto');

                        $('#dialogCategoryInput').val('');
                        $('#dialogCategoryInput').focus();

                        // Hide the dialog box and clear the input
                        $('#dialogBox').modal('hide');

                        // Show success message
                        alert('Success', 'Category saved successfully');
                    },
                    error: function(xhr, status, error) {
                        //Hide category add dialog box
                        $('#dialogBox').hide();
                        // Show error message
                        alert('Error', 'Failed to fetch categories');

                        //Remove background backdrop
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();

                        // Re-enable scrolling
                        $('body').css('overflow', 'auto');
                    }
                });

            });
            // Event handler for fully shown modal
            $('#dialogBox').on('shown.bs.modal', function() {
                $('#dialogCategoryInput').focus();
            });


            //when click minus button
            $('#removeCategoryButton').click(function() {
                // Show the remove dialog box
                $('#removeDialogBox').modal('show');

                //Remove error message
                $('#errorRemoveContainer').text("");

                $.ajax({
                    url: "{{ route('categories.getCategories') }}",
                    method: "GET",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        //Update the select box with the updated category options
                        $('#removeDialogCategorySelect').html(response);

                    },
                    error: function(xhr, status, error) {
                        // Show error message
                        alert('Error', 'Failed to fetch categories');
                    }
                });

            });

            // Remove dialog box remove button click event
            $('#removeDialogButton').click(function() {
                var selectedValue = $('#removeDialogCategorySelect').val();

                //Remove category
                if (selectedValue) {
                    //Remove error message
                    $('#errorRemoveContainer').text("");

                    // Perform AJAX request to delete the category
                    $.ajax({
                        url: "{{ route('categories.destroy', '') }}/" + selectedValue,
                        method: "DELETE",
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            // Remove the selected option from the select box
                            $('#selectedBox option').each(function() {
                                if ($(this).val() === selectedValue) {
                                    $(this).remove();
                                }
                            });

                            // Hide the dialog box and clear the input
                            $('#removeDialogBox').modal('hide');

                            //Remove background backdrop
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();

                            // Re-enable scrolling
                            $('body').css('overflow', 'auto');

                            // Show success message
                            alert('Success', 'Category deleted successfully');
                        },
                        error: function(xhr, status, error) {
                            //Hide category remove dialog box
                            $('#removeDialogBox').modal('hide');
                            // Show error message
                            alert('Error',
                                'You cannot delete a category that is being used by an item.'
                            );
                        },
                        complete: function() {
                            // Re-fetch the category list and populate the select box
                            $.ajax({
                                url: "{{ route('categories.getCategories') }}",
                                method: "GET",
                                data: {
                                    "_token": "{{ csrf_token() }}"
                                },
                                success: function(response) {
                                    //Update the select box with the updated category options
                                    // var categoryOptions = JSON.parse(response);
                                    $('#selectedBox').html(response);

                                },
                                error: function(xhr, status, error) {
                                    // Show error message
                                    alert('Error', 'Failed to fetch categories');
                                }
                            });
                        }
                    });

                } else {
                    //selectedValue is null
                    //show error
                    $('#errorRemoveContainer').text("Please select an option.");
                }
            });
        });

        // Show the selected image when a file is chosen
        document.getElementById('photo').addEventListener('change', function() {

            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('previewImage').src = '';
            }
        });

        // Remove the chosen file when the remove button is clicked
        document.getElementById('removePhoto').addEventListener('click', function() {

            var photoInput = document.getElementById('photo');

            $('#removeImageFlag').val(true);
            // Reset the value to remove the chosen file
            photoInput.value = null;
            document.getElementById('previewImage').src = '';
        });
    </script>
@endsection
