<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BitByBTC - @isset($data['pageName']) {{ ucfirst($data['pageName']) }}
        @else
            {{ ucfirst($pageName) }} @endif
        </title>
        <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('js/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="{{ asset('img/giobit-fav.png') }}" type="image/png" />
        <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('css/dataTables.bootstrap.min.css') }}">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css">
        <link rel="stylesheet"
        href="{{ asset('js/plugins/tempusdominus-bootstrap-4/cs') }}s/tempusdominus-bootstrap-4.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('css/menu.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('css/owl.carousel.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('css/p2pex.css') }}" />

        <link rel="stylesheet" type="text/css" href="{{ asset('css/responsive.css') }}" />
        <link rel="stylesheet" href="{{ asset('js/plugins/toastr/toastr.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('js/plugins/select2/css/select2.min.css') }}" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('js/plugins/js-confirm/jquery-confirm.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-confirm.css') }}" />
        <style>
            .form-control {
                height: 43px !important;
            }

            .error {
                color: red;
            }
        </style>
    </head>

    <body style="background:#fff;">
        <!--header-start--->
        @include('layout.header-menu')
        <!--header-end--->
        <div class="clear"></div>
