@extends('layouts.app')

@section('title')
    Item Registration Form
@endsection

@section('header')
    <link rel="stylesheet" href="/css/register.css">
    <style>
        .reach {
            border-bottom: 2px solid #007bff;
        }
    </style>
@endsection



<!-- Excel import errors -->
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

        <div class="row mt-2">

            <!-- Excel upload error -->
            @if ($errors->has('file'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <ul>
                        @foreach ($errors->get('file') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @if (session('message'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    {{ session('message') }}
                </div>
            @endif

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
    </div>

    <!-- Register Form -->
    <section>
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-lg-10">

                    <!-- Radio button to switch form -->
                    <div class="mt-3 text-center radio">
                        <div class="form-check form-check-inline me-5">
                            <input class="form-check-input" type="radio" name="registerType" value="normal" id="type1"
                                checked>
                            <label class="form-check-label link-label text-white"
                                for="type1">{{ __('messages.Normal Register') }}</label>
                        </div>
                        <div class="form-check form-check-inline ms-5">
                            <input class="form-check-input" type="radio" name="registerType" id="type2"
                                value="excel">
                            <label class="form-check-label link-label text-white"
                                for="type2">{{ __('messages.Excel Upload Register') }}
                            </label>
                        </div>
                    </div>

                    <!-- Normal Register Form -->
                    <div class="wrapper" id="normalForm">
                        <form method="POST" action=" {{ route('items.store') }} " enctype="multipart/form-data">
                            @csrf
                            <div class="h5 font-weight-bold text-center mb-3">{{ __('messages.Items Registration') }}
                            </div>
                            <div class="form-group d-flex align-items-center">
                                <div class="">
                                    <label for="itemID" class="form-label text-white">{{ __('messages.Item ID') }}</label>
                                    <input type="text" id="itemID" name="item_id" class="form-control"
                                        value="{{ $itemId }}" readonly>
                                </div>
                            </div>
                            @error('item_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-group d-flex align-items-center mt-5">
                                <div class="">
                                    <label for="itemCode" class="form-label text-white">{{ __('messages.Item Code') }}<span
                                            style="color:red;">*</span></label>
                                    <input type="text" id="itemCode" class="form-control" name="item_code"
                                        autocomplete="off" placeholder="Enter Item Code" value="{{ old('item_code') }}">
                                </div>
                            </div>
                            @error('item_code')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                            <div class="form-group d-flex align-items-center mt-5">
                                <div class="">
                                    <label for="itemName" class="form-label text-white">{{ __('messages.Item Name') }}<span
                                            style="color:red;">*</span></label>
                                    <input type="text" id="itemName" class="form-control" name="item_name"
                                        autocomplete="off" placeholder="Enter Item Name" value="{{ old('item_name') }}">
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
                                    <input type="text" id="safetyStock" class="form-control" name="safety_stock"
                                        autocomplete="off" placeholder="Enter Safety Stock"
                                        value="{{ old('safety_stock') }}" maxlength="10">
                                </div>
                            </div>
                            @error('safety_stock')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

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
                                                {{ $category->id == old('categoryName') ? 'selected' : '' }}>
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


                            @error('categoryName')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class=" d-flex align-items-center mt-5">
                                <div class="mb-3 col-md-12">
                                    <label for="receivedDate"
                                        class="form-label text-white">{{ __('messages.Received Date') }}<span
                                            style="color:red;">*</span></label>
                                    <input type="date" id="receivedDate" class="form-control" name="received_date"
                                        autocomplete="off" value="{{ old('received_date') }}">
                                </div>
                            </div>
                            @error('received_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-group mt-5">
                                <label for="description"
                                    class="form-label text-white">{{ __('messages.Description') }}</label>
                                <textarea id="description" class="form-control" name="description" value="{{ old('description') }}"></textarea>
                            </div>
                            <label for="photo"
                                class="form-label text-white mt-5">{{ __('messages.Upload Photo') }}</label>
                            <div class="input-group">
                                <input type="file" id="photo" class="form-control" name="photo"
                                    accept="image/*">
                                <button type="button" id="removePhoto"
                                    class="btn btn-danger ms-2">{{ __('messages.Remove') }}</button>
                            </div>
                            @error('photo')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <!--Show chosen image from file input in UI  -->
                            <div class="mb-3 mt-3">
                                <img src="" id="previewImage" style="max-width: 200px;">
                            </div>
                            <div class="mt-3 d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary mb-3">{{ __('messages.Save') }}</button>
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
                                        <option value="">{{ __('messages.Category Name') }}</option>
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



                    <!-- Excel Upload Register Form -->
                    <div class="wrapper" id="excelForm">
                        <form action="{{ route('items.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <div class="justify-content-right mb-3">
                                    <a href=" {{ route('items.excelFormat') }} " class="btn btn-secondary"
                                        id="downloadBtn">{{ __('messages.Excel Download Format') }}</a>
                                </div>
                                <input type="file" id="fileInput" class="custom-file-input" name="file">
                                <label for="fileInput" class="file-input-button">
                                    <span class="file-input-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                            <path
                                                d="M288 109.3V352c0 17.7-14.3 32-32 32s-32-14.3-32-32V109.3l-73.4 73.4c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l128-128c12.5-12.5 32.8-12.5 45.3 0l128 128c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L288 109.3zM64 352H192c0 35.3 28.7 64 64 64s64-28.7 64-64H448c35.3 0 64 28.7 64 64v32c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V416c0-35.3 28.7-64 64-64zM432 456a24 24 0 1 0 0-48 24 24 0 1 0 0 48z" />
                                        </svg>
                                    </span>
                                    <span style="margin-top: 5px;">{{ __('messages.Browse') }}</span>
                                </label>
                                <div class="form-group mt-3">
                                    <input type="text" class="form-control" id="fileName" readonly>
                                </div>
                                <div class="mt-3 d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary mb-3"
                                        id="uploadBtn">{{ __('messages.Save') }}</button>
                                </div>
                            </div>
                        </form>
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

            // Initially hide the excel form
            $('#excelForm').hide();

            // Check if there is a stored value for the registerType
            var storedRegisterType = localStorage.getItem('registerType');
            if (storedRegisterType) {
                $('input[name="registerType"]').val([storedRegisterType]);
                showSelectedForm(storedRegisterType);
            }

            // Radio button change event
            $('input[name="registerType"]').change(function() {
                var selectedValue = $('input[name="registerType"]:checked').val();

                // Show the selected form based on the radio button value
                showSelectedForm(selectedValue);

                // Store the selected radio button value in localStorage
                localStorage.setItem('registerType', selectedValue);
            });

            function showSelectedForm(selectedValue) {
                if (selectedValue === 'normal') {
                    $('#normalForm').show();
                    $('#excelForm').hide();
                } else if (selectedValue === 'excel') {
                    $('#normalForm').hide();
                    $('#excelForm').show();
                }
            }

            // Update file name when file is selected
            $('#fileInput').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $('#fileName').val(fileName);
            });

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
            // Reset the value to remove the chosen file
            photoInput.value = null;
            document.getElementById('previewImage').src = '';
        });
    </script>
@endsection
