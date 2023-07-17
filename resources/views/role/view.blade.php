@extends('layouts.app')

@section('title', $page_title)

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <!--begin::Notice-->
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-5">
                <div class="card-title">
                    <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Button-->
                    <a href="{{ route('role') }}" class="btn btn-secondary btn-sm font-weight-bolder"> 
                        <i class="fas fa-arrow-circle-left"></i> Back
                    </a>
                    <!--end::Button-->
                </div>
            </div>
        </div>
        <!--end::Notice-->
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header pt-5 text-center">
                <h5 class="text-center w-100 m-0">{{ $role->role_name }} Role Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <ul id="permission" class="text-left"  style="list-style: none;">
                            @if (!empty($permission_modules))
                                @foreach ($permission_modules as $menu)
                                    @if ($menu->submenu->isEmpty())
                                        <li class="@if($menu->type == 2) {{ 'mb-5' }} @endif">
                                            @if(collect($role_module)->contains($menu->id)) 
                                            <i class="fas fa-check-circle text-success"></i>
                                            @else
                                            <i class="fas fa-times-circle text-danger"></i>
                                            @endif
                                            <b>{!! $menu->type == 1 ? $menu->divider_title.' <small>(Divider)</small>' : $menu->module_name !!}</b>
                                            @if (!$menu->permission->isEmpty())
                                                <ul style="list-style: none;">
                                                    @foreach ($menu->permission as $permission)
                                                        <li>
                                                            
                                                            @if(collect($role_permission)->contains($permission->id)) 
                                                            <i class="fas fa-check-circle text-success"></i>
                                                            @else
                                                            <i class="fas fa-times-circle text-danger"></i>
                                                            @endif
                                                            {{ $permission->name }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @else 
                                    <li>
                                        
                                        @if(collect($role_module)->contains($menu->id)) 
                                        <i class="fas fa-check-circle text-success"></i>
                                        @else
                                        <i class="fas fa-times-circle text-danger"></i>
                                        @endif
                                        <b>{!! $menu->type == 1 ? $menu->divider_title.' <small>(Divider)</small>' : $menu->module_name !!}</b>
                                            <ul style="list-style: none;">
                                                @foreach ($menu->submenu as $submenu)
                                                    @if ($submenu->submenu->isEmpty())
                                                        <li class="@if($menu->type == 2) {{ 'mb-5' }} @endif">
                                                            @if(collect($role_module)->contains($submenu->id)) 
                                                            <i class="fas fa-check-circle text-success"></i>
                                                            @else
                                                            <i class="fas fa-times-circle text-danger"></i>
                                                            @endif
                                                            <b>{{ $submenu->module_name }}</b>
                                                            @if (!$submenu->permission->isEmpty())
                                                                <ul style="list-style: none;">
                                                                    @foreach ($submenu->permission as $permission)
                                                                    <li>
                                                                        @if(collect($role_permission)->contains($permission->id)) 
                                                                        <i class="fas fa-check-circle text-success"></i>
                                                                        @else
                                                                        <i class="fas fa-times-circle text-danger"></i>
                                                                        @endif
                                                                        {{ $permission->name }}
                                                                    </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </li>
                                                    @else 
                                                    <li>
                                                        @if(collect($role_module)->contains($menu->id)) 
                                                        <i class="fas fa-check-circle text-success"></i>
                                                        @else
                                                        <i class="fas fa-times-circle text-danger"></i>
                                                        @endif
                                                        <b>{{ $submenu->module_name }}</b>
                                                        <ul style="list-style: none;">
                                                            @foreach ($submenu->submenu as $sub_submenu)
                                                                <li class="@if($submenu->type == 2) {{ 'mb-5' }} @endif">
                                                                    @if(collect($role_module)->contains($sub_submenu->id)) 
                                                                    <i class="fas fa-check-circle text-success"></i>
                                                                    @else
                                                                    <i class="fas fa-times-circle text-danger"></i>
                                                                    @endif
                                                                    <b>{{ $sub_submenu->module_name }}</b>
                                                                    @if (!$sub_submenu->permission->isEmpty())
                                                                        <ul style="list-style: none;">
                                                                            @foreach ($sub_submenu->permission as $permission)
                                                                            <li>
                                                                                @if(collect($role_permission)->contains($permission->id)) 
                                                                                <i class="fas fa-check-circle text-success"></i>
                                                                                @else
                                                                                <i class="fas fa-times-circle text-danger"></i>
                                                                                @endif
                                                                                {{ $permission->name }}
                                                                            </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                @endforeach
                                            </ul>
                                    </li>
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>
@endsection

