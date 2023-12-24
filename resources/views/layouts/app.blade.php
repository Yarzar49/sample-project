<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <title>
        @yield('title')
    </title>
    <style>
         .ui-autocomplete {
            max-height: 200px;
            overflow-y: auto;
            overflow-x: hidden;
            border: 0px;
        }
    </style>
    @yield('header')
</head>

<body>
    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <img src="https://cdn-icons-png.flaticon.com/128/11213/11213134.png" alt="Logo" width="32"
                height="28" class="d-inline-block align-text-top">
            <a class="navbar-brand beautiful-text ms-1" href="{{ route('items.show') }}">
                {{ __('messages.Form Title') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse  justify-content-end" id="navbarNav">
                <ul class="navbar-nav ">
                    <li class="nav-item me-5">
                        <a class="nav-link reach active" aria-current="page"
                            href="{{ route('items.register') }}">{{ __('messages.Register') }}</a>
                    </li>
                    <li class="nav-item me-5">
                        <a class="nav-link reachList active" aria-current="page"
                            href="{{ route('items.show') }}">{{ __('messages.List') }}</a>
                    </li>
                    <li>
                        <a href=" {{ route('items.category-dashboard') }}"
                            class="nav-link reachCategory active me-2">{{ __('messages.Category') }} Dashboard
                        </a>
                    </li>
                    <li class="nav-item dropdown me-5">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            @if (app()->getLocale() == 'en')
                                <img src="https://th.bing.com/th/id/OIP.e_bbO_MwobphE7AiIzUzyQHaEA?pid=ImgDet&rs=1"
                                    class="me-1" alt="english flag" width="20px" height="20px">English
                            @elseif(app()->getLocale() == 'mm')
                                <img src="https://th.bing.com/th/id/OIP.L7vH9o88A0837m6wELHTewHaE7?w=248&h=180&c=7&r=0&o=5&dpr=1.5&pid=1.7"
                                    class="me-1" alt="myanmar flag" width="20px"
                                    height="20px">{{ __('messages.Myanmar') }}
                            @else
                                {{ $selectedData->name }}
                            @endif
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item{{ app()->getLocale() == 'en' ? ' active' : '' }}"
                                    href="{{ route('language.switch', 'en') }}"><img
                                        src="https://th.bing.com/th/id/OIP.e_bbO_MwobphE7AiIzUzyQHaEA?pid=ImgDet&rs=1"
                                        class="me-1" alt="english flag" width="20px" height="20px">English</a></li>
                            <li><a class="dropdown-item{{ app()->getLocale() == 'mm' ? ' active' : '' }}"
                                    href="{{ route('language.switch', 'mm') }}"><img
                                        src="https://th.bing.com/th/id/OIP.L7vH9o88A0837m6wELHTewHaE7?w=248&h=180&c=7&r=0&o=5&dpr=1.5&pid=1.7"
                                        class="me-1" alt="myanmar flag" width="20px"
                                        height="20px">{{ __('messages.Myanmar') }}</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <div class="dropdown me-5">
                            <a class="btn dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                                    <path
                                        d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z" />
                                </svg>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link " href="{{ route('logout') }}" style="color:red;">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                            <path
                                                d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z" />
                                        </svg><span class="ms-2">{{ __('messages.Logout') }}</span>
                                    </a></li>
                            </ul>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    @yield('content')


</body>

</html>
