<x-mobile-header/>
<div class="d-flex flex-column flex-root">
    <div class="d-flex flex-row flex-column-fluid page">
        <x-sidebar/>
        <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
            <x-header/>
            <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
                @include('layouts.includes.sub-header')
                @yield('content')
            </div>
            <x-footer/>
        </div>
    </div>
</div>
<x-user-profile/>
<x-scroll-top/>
@include('layouts.includes.scripts')