@extends('layouts.app')

@section('title', $page_title)

@push('styles')
<link href="css/jquery.nestable.min.css" rel="stylesheet" type="text/css" />
@endpush

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
                    <a href="{{ route('menu') }}" type="button" class="btn btn-secondary btn-sm font-weight-bolder mr-2"> <i class="fas fa-arrow-circle-left"></i> Back</a>
                    @if (permission('menu-module-add'))
                    <a href="javascript:void(0);" onclick="showFormModal('Add New Module','Save')" class="btn btn-primary btn-sm font-weight-bolder"> 
                        <i class="fas fa-plus-circle"></i> Add New
                    </a>
                    @endif
                    <!--end::Button-->
                </div>
            </div>
        </div>
        <!--end::Notice-->
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap py-5">
                <h5 class="card-item">Drag and drop the menu item below to re-arrange them</h5>
            </div>
            <div class="card-body menu-builder">
                <div class="dd"> </div>
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>
@include('module.modal')
@endsection

@push('scripts')
<script src="js/jquery.nestable.min.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
    menuItems();
    function menuItems(){
        $.ajax({
            url:"{{ route('menu.items') }}",
            type:"POST",
            data:{id:"{{ $menu->id }}",_token:_token},
            success:function(data){
                $('.dd').html('');
                $('.dd').html(data);
                $('.dd').nestable({maxDepth:3});//initialized nestable with max depth 2
            },
            error: function (xhr, ajaxOption, thrownError) {
                console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
            }
        });
    }

    $(window).mousemove(function (e) {
        if ($('.dd-dragel') && $('.dd-dragel').length > 0 && !$('html, body').is(':animated')) {
            var bottom = $(window).height() - 50,
                top = 50;

            if (e.clientY > bottom && ($(window).scrollTop() + $(window).height() < $(document).height() - 100)) {
                $('html, body').animate({
                    scrollTop: $(window).scrollTop() + 300
                }, 600);
            }
            else if (e.clientY < top && $(window).scrollTop() > 0) {
                $('html, body').animate({
                    scrollTop: $(window).scrollTop() - 300
                }, 600);
            } else {
                $('html, body').finish();
            }
        }
    });

    $(document).on('change','.dd',function(e){
        $.post('{{ route("menu.module.order",["menu"=>$menu->id]) }}',{
            order:JSON.stringify($('.dd').nestable('serialize')),_token:_token
        }, function(data){
            notification('success','Menu order updated successfully');
            menuItems();
        });
    });


    $(document).on('click', '#save-btn', function () {
        let form = document.getElementById('store_or_update_form');
        let formData = new FormData(form);
        $.ajax({
            url: "{{route('menu.module.store.or.update')}}",
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
                $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                $('#store_or_update_form').find('.error').remove();
                if (data.status == false) {
                    $.each(data.errors, function (key, value) {
                        $('#store_or_update_form input#' + key).addClass('is-invalid');
                        $('#store_or_update_form textarea#' + key).addClass('is-invalid');
                        $('#store_or_update_form select#' + key).parent().addClass('is-invalid');
                        $('#store_or_update_form #' + key).parent().append(
                            '<small class="error text-danger">' + value + '</small>');
                    });
                } else {
                    notification(data.status, data.message);
                    if (data.status == 'success') {
                        menuItems();
                        $('.divider_fields').addClass('d-none');
                        $('.item_fields').addClass('d-none');
                        $('#store_or_update_modal').modal('hide');
                    }
                }

            },
            error: function (xhr, ajaxOption, thrownError) {
                console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
            }
        });
        
    });

    $(document).on('click', '.edit_data', function () {
        let id = $(this).data('id');
        $('#store_or_update_form')[0].reset();
        $('#store_or_update_form .select').val('');
        $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
        $('#store_or_update_form').find('.error').remove();
        if (id) {
            $.ajax({
                url: "{{route('menu.module.edit')}}",
                type: "POST",
                data: { id: id,_token: _token},
                dataType: "JSON",
                success: function (data) {
                    if(data.status == 'error'){
                        notification(data.status,data.message)
                    }else{
                        $('#store_or_update_form #update_id').val(data.id);
                        $('#store_or_update_form #type').val(data.type);
                        setItemType(data.type)
                        if(data.type == 1){
                            $('#store_or_update_form #divider_title').val(data.divider_title);
                        }else{
                            $('#store_or_update_form #module_name').val(data.module_name);
                            $('#store_or_update_form #url').val(data.url);
                            $('#store_or_update_form #icon_class').val(data.icon_class);
                            $('#store_or_update_form #target').val(data.target);
                        } 
                        $('#store_or_update_form .selectpicker').selectpicker('refresh');

                        $('#store_or_update_modal').modal({
                            keyboard: false,
                            backdrop: 'static',
                        });
                        $('#store_or_update_modal .modal-title').html(
                            '<i class="fas fa-edit"></i> <span>Edit ' + data.menu_name + '</span>');
                        $('#store_or_update_modal #save-btn').text('Update');
                    }
                    
                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        }
    });

    $(document).on('click', '.delete_data', function () {
        let id    = $(this).data('id');
        let name  = $(this).data('name');
        Swal.fire({
            title: 'Are you sure to delete ' + name + '?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "{{ route('menu.module.delete') }}",
                    type: "POST",
                    data: { id: id, _token: _token},
                    dataType: "JSON",
                }).done(function (response) {
                    if (response.status == "success") {
                        Swal.fire("Deleted", response.message, "success").then(function () {
                            menuItems();
                        });
                    }
                    if (response.status == "error") {
                        Swal.fire('Oops...', response.message, "error");
                    }
                }).fail(function () {
                    Swal.fire('Oops...', "Somthing went wrong with ajax!", "error");
                });
            }
        });
    });
});

function setItemType(type){
    if(type == 1){
        $('.divider_fields').removeClass('d-none');
        $('.item_fields').addClass('d-none');
    }else{
        $('.divider_fields').addClass('d-none');
        $('.item_fields').removeClass('d-none');
    }
}
</script>
@endpush