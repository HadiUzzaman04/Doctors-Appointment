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
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form id="saveDataForm" method="post">
                            @csrf 
                            <div class="row">
                                <input type="hidden" name="update_id" value="@isset($role){{$role->id}}@endisset" id="update_id">
                                <div class="form-group col-md-12 required">
                                    <label for="role_name">Role Name</label>
                                    <input type="text" class="form-control" name="role_name" id="role_name" value="@isset($role){{$role->role_name}}@endisset" placeholder="Enter role name">
                                </div>
                                <div class="col-md-12">
                                    <ul id="permission" class="text-left">
                                        @if (!empty($permission_modules))
                                            @foreach ($permission_modules as $menu)
                                                @if ($menu->submenu->isEmpty())
                                                    <li>
                                                        <input type="checkbox" name="module[]" class="module" value="{{ $menu->id }}"
                                                        @isset($role_module) @if(collect($role_module)->contains($menu->id)) {{ 'checked' }} @endif @endisset > 
                                                        {!! $menu->type == 1 ? $menu->divider_title.' <small>(Divider)</small>' : $menu->module_name !!}
                                                        @if (!$menu->permission->isEmpty())
                                                            <ul>
                                                                @foreach ($menu->permission as $permission)
                                                                    <li><input type="checkbox" name="permission[]" value="{{ $permission->id }}"
                                                                        @isset($role_permission) @if(collect($role_permission)->contains($permission->id)) {{ 'checked' }} @endif @endisset />
                                                                            {{ $permission->name }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @else 
                                                <li>
                                                    <input type="checkbox" name="module[]" class="module" value="{{ $menu->id }}"
                                                    @isset($role_module) @if(collect($role_module)->contains($menu->id)) {{ 'checked' }} @endif @endisset > 
                                                    {!! $menu->type == 1 ? $menu->divider_title.' <small>(Divider)</small>' : $menu->module_name !!}
                                                    <ul>
                                                        @foreach ($menu->submenu as $submenu)
                                                            @if ($submenu->submenu->isEmpty())
                                                                <li>
                                                                    <input type="checkbox" name="module[]" class="module" value="{{ $submenu->id }}"
                                                                    @isset($role_module) @if(collect($role_module)->contains($submenu->id)) {{ 'checked' }} @endif @endisset >
                                                                        {{ $submenu->module_name }}
                                                                    @if (!$submenu->permission->isEmpty())
                                                                        <ul>
                                                                            @foreach ($submenu->permission as $permission)
                                                                            <li><input type="checkbox" name="permission[]" value="{{ $permission->id }}" 
                                                                                @isset($role_permission) @if(collect($role_permission)->contains($permission->id)) {{ 'checked' }} @endif @endisset />
                                                                                {{ $permission->name }}
                                                                            </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </li>
                                                            @else
                                                            <li>
                                                                <input type="checkbox" name="module[]" class="module" value="{{ $submenu->id }}"
                                                                @isset($role_module) @if(collect($role_module)->contains($submenu->id)) {{ 'checked' }} @endif @endisset > 
                                                                {!! $submenu->module_name !!}
                                                                <ul>
                                                                    @foreach ($submenu->submenu as $sub_submenu)
                                                                    <li>
                                                                        <input type="checkbox" name="module[]" class="module" value="{{ $sub_submenu->id }}"
                                                                        @isset($role_module) @if(collect($role_module)->contains($sub_submenu->id)) {{ 'checked' }} @endif @endisset >
                                                                            {{ $sub_submenu->module_name }}
                                                                        @if (!$sub_submenu->permission->isEmpty())
                                                                            <ul>
                                                                                @foreach ($sub_submenu->permission as $permission)
                                                                                <li><input type="checkbox" name="permission[]" value="{{ $permission->id }}" 
                                                                                    @isset($role_permission) @if(collect($role_permission)->contains($permission->id)) {{ 'checked' }} @endif @endisset />
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
                                <div class="col-md-12 pt-4 text-center">
                                    @if(isset($role))
                                    <a href="{{ route('role') }}" class="btn btn-danger btn-sm font-weight-bolder">Cancel</a>
                                    @else
                                    <button type="reset" class="btn btn-danger btn-sm">Reset</button>
                                    @endif
                                    <button type="button" class="btn btn-primary btn-sm" id="save-btn">@if(isset($role)) {{ 'Update' }} @else {{ 'Save' }} @endif</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>
@endsection

@push('scripts')
<script src="js/tree.js"></script>
<script>
$(document).ready(function(){
    $('#permission').treed(); //intialized tree js
    $('input[type=checkbox]').click(function(){
        $(this).next().find('input[type=checkbox]').prop('checked',this.checked);
        $(this).parents('ul').prev('input[type=checkbox]').prop('checked', function(){
            return $(this).next().find(':checked').length;
        });
    });

    $(document).on('click', '#save-btn', function () {
        let form = document.getElementById('saveDataForm');
        let formData = new FormData(form);
        if($('.module:checked').length >= 1){
            $.ajax({
                url: "{{route('role.store.or.update')}}",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function(){
                    $('#save-btn').addClass('spinner spinner-white spinner-right');
                },
                complete: function(){
                    $('#save-btn').removeClass('spinner spinner-white spinner-right');
                },
                success: function (data) {
                    $('#saveDataForm').find('.is-invalid').removeClass('is-invalid');
                    $('#saveDataForm').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            $('#saveDataForm input#' + key).addClass('is-invalid');
                            $('#saveDataForm #' + key).parent().append(
                            '<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') {
                            window.location.replace("{{ route('role') }}");
                        }
                    }

                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        }else{
            notification('error','Please check at least one menu');
        }
        
    });
});
</script>
@endpush