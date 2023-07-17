<div id="kt_header_mobile" class="header-mobile  header-mobile-fixed ">
    <div class="mobile-logo">

            @if (config('settings.logo'))
            <a href="{{ url('/') }}">
                <img src="{{ asset('storage/'.LOGO_PATH.config('settings.logo'))}}" style="width: 90px;" alt="Logo" />
            </a>
            @else
            <h3 class="text-white">{{ config('settings.title') ? config('settings.title') : env('APP_NAME') }}</h3>
            @endif
    </div>
    <div class="d-flex align-items-center">

        <button class="btn p-0 burger-icon burger-icon-left" id="kt_aside_mobile_toggle"><span></span></button>

        {{-- <button class="btn p-0 burger-icon ml-4" id="kt_header_mobile_toggle"><span></span></button> --}}

        <button class="btn btn-hover-text-primary p-0 ml-2" id="kt_header_mobile_topbar_toggle">
            <span class="svg-icon svg-icon-xl"><i class="fas fa-user text-white"></i></span>
        </button>

    </div>
</div>