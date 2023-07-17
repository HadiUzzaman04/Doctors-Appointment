<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <base href="{{ asset('/') }}" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('settings.title') ? config('settings.title') : config('app.name', 'Laravel') }} - @yield('title')</title>
		<link rel="icon" type="image/png" href="{{ 'storage/'.LOGO_PATH.config('settings.favicon')}}">
		<link rel="shortcut icon" href="{{  'storage/'.LOGO_PATH.config('settings.favicon')}}" />
        @include('layouts.includes.styles')
    </head>
    <body id="kt_body" class="quick-panel-right demo-panel-right offcanvas-right header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-minimize-hoverable aside-fixed page-loading">
        <div id="preloader">
            <div id="loading">
                <i class="fa fa-spinner fa-spin fa-3x fa-fw text-primary" aria-hidden="true" style="font-size: 80px;"></i>
            </div>
        </div>
        <x-layout/>
    </body>
</html>
