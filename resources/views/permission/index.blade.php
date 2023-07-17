@extends('layouts.app')

@section('title', $page_title)

@push('styles')
<link href="plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
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
                    @if (permission('permission-access'))
                    <a href="javascript:void(0);" onclick="showFormModal('Add New Module Permission','Save')" class="btn btn-primary btn-sm font-weight-bolder"> 
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
                <form method="POST" id="form-filter" class="col-md-12 px-0">
                    <div class="row">
                        <x-form.textbox labelName="Permission Name" name="name" col="col-md-4" />
                        <x-form.selectbox labelName="Module" name="module_id" required="required" col="col-md-4" class="selectpicker">
                            @foreach ($modules as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </x-form.selectbox>
                        <div class="col-md-4">
                            <div style="margin-top:28px;">    
                                <button id="btn-reset" class="btn btn-danger btn-sm btn-elevate btn-icon float-right" type="button"
                                data-toggle="tooltip" data-theme="dark" title="Reset">
                                <i class="fas fa-undo-alt"></i></button>

                                <button id="btn-filter" class="btn btn-primary btn-sm btn-elevate btn-icon mr-2 float-right" type="button"
                                data-toggle="tooltip" data-theme="dark" title="Search">
                                <i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <!--begin: Datatable-->
                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="dataTable" class="table table-bordered table-hover">
                                <thead class="bg-primary">
                                    <tr>
                                        @if (permission('permission-bulk-delete'))
                                        <th>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                                <label class="custom-control-label" for="select_all"></label>
                                            </div>
                                        </th>
                                        @endif
                                        <th>Sl</th>
                                        <th>Module</th>
                                        <th>Permission Name</th>
                                        <th>Permission Slug</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>
@include('permission.create-modal')
@include('permission.edit-modal')
@endsection

@push('scripts')
<script src="plugins/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script>
var table;
$(document).ready(function(){

    table = $('#dataTable').DataTable({
        "processing": true, //Feature control the processing indicator
        "serverSide": true, //Feature control DataTable server side processing mode
        "order": [], //Initial no order
        "responsive": true, //Make table responsive in mobile device
        "bInfo": true, //TO show the total number of data
        "bFilter": false, //For datatable default search box show/hide
        "lengthMenu": [
            [5, 10, 15, 25, 50, 100, 1000, 10000, -1],
            [5, 10, 15, 25, 50, 100, 1000, 10000, "All"]
        ],
        "pageLength": 25, //number of data show per page
        "language": { 
            processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
            emptyTable: '<strong class="text-danger">No Data Found</strong>',
            infoEmpty: '',
            zeroRecords: '<strong class="text-danger">No Data Found</strong>'
        },
        "ajax": {
            "url": "{{route('menu.module.permission.datatable.data')}}",
            "type": "POST",
            "data": function (data) {
                data.name      = $("#form-filter #name").val();
                data.module_id = $("#form-filter #module_id").val();
                data._token    = _token;
            }
        },
        "columnDefs": [{
                 @if (permission('permission-bulk-delete'))
                "targets": [0,5],
                @else 
                "targets": [4],
                @endif
                "orderable": false,
                "className": "text-center"
            },
            {
                @if (permission('permission-bulk-delete'))
                "targets": [1,2,3,4],
                @else 
                "targets": [0,1,2,3],
                @endif
                
                "className": "text-center"
            }
        ],
        "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",

        "buttons": [
            @if (permission('permission-report'))
            {
                'extend':'colvis','className':'btn btn-secondary btn-sm text-white','text':'Column','columns': ':gt(0)'
            },
            {
                    "extend": 'print',
                    'text':'Print',
                    'className':'btn btn-secondary btn-sm text-white',
                    "title": "{{ $page_title }} List",
                    "orientation": "landscape", //portrait
                    "pageSize": "A4", //A3,A5,A6,legal,letter
                    'columns': ':gt(0)',
                    "exportOptions": {
                        columns: function (index, data, node) {
                            return table.column(index).visible();
                        }
                    },
                    customize: function (win) {
                        $(win.document.body).addClass('bg-white');
                        $(win.document.body).find('table thead').css('background-color','#034d97');
                        $(win.document.body).find('table tfoot tr').css('background-color','#034d97');
                        $(win.document.body).find('h1').css('text-align', 'center');
                        $(win.document.body).find('h1').css('font-size', '15px');
                        $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                    },
                },
                {
                    "extend": 'csv',
                    'text':'CSV',
                    'className':'btn btn-secondary btn-sm text-white',
                    "title": "{{ $page_title }} List",
                    "filename": "{{ strtolower(str_replace(' ','-',$page_title)) }}-list",
                    "exportOptions": {
                        columns: function (index, data, node) {
                            return table.column(index).visible();
                        }
                    }
                },
                {
                    "extend": 'excel',
                    'text':'Excel',
                    'className':'btn btn-secondary btn-sm text-white',
                    "title": "{{ $page_title }} List",
                    "filename": "{{ strtolower(str_replace(' ','-',$page_title)) }}-list",
                    "exportOptions": {
                        columns: function (index, data, node) {
                            return table.column(index).visible();
                        }
                    }
                },
                {
                    "extend": 'pdf',
                    'text':'PDF',
                    'className':'btn btn-secondary btn-sm text-white',
                    "title": "{{ $page_title }} List",
                    "filename": "{{ strtolower(str_replace(' ','-',$page_title)) }}-list",
                    "orientation": "landscape", //portrait
                    "pageSize": "A4", //A3,A5,A6,legal,letter
                    "exportOptions": {
                        columns: function (index, data, node) {
                            return table.column(index).visible();
                        }
                    },
                },
            @endif
            @if (permission('permission-bulk-delete'))
            {
                'className':'btn btn-danger btn-sm delete_btn d-none text-white',
                'text':'Delete',
                action:function(e,dt,node,config){
                    multi_delete();
                }
            }
            @endif
        ],
    });

    $('#btn-filter').click(function () {
        table.ajax.reload();
    });

    $('#btn-reset').click(function () {
        $('#form-filter')[0].reset();
        $('#form-filter .selectpicker').selectpicker('refresh');
        table.ajax.reload();
    });

    $(document).on('click', '#save-btn', function () {
        let form = document.getElementById('store_or_update_form');
        let formData = new FormData(form);
        let url = "{{route('menu.module.permission.store')}}";
        let id = $('#update_id').val();
        let method;
        if (id) {
            method = 'update';
        } else {
            method = 'add';
        }
        $.ajax({
            url: url,
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
                        var key = key.split('.').join('_');
                        $('#store_or_update_form select#' + key).parent().addClass('is-invalid');
                        $('#store_or_update_form #' + key).parent().append(
                            '<small class="error text-danger">' + value + '</small>');
                        $('#store_or_update_form table').find('#'+key).addClass('is-invalid');
                    });
                } else {
                    notification(data.status, data.message);
                    if (data.status == 'success') {
                        if (method == 'update') {
                            table.ajax.reload(null, false);
                        } else {
                            table.ajax.reload();
                        }
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
        if (id) {
            $.ajax({
                url: "{{route('menu.module.permission.edit')}}",
                type: "POST",
                data: { id: id,_token: _token},
                dataType: "JSON",
                success: function (data) {
                    $('#update_form #update_id').val(data.id);
                    $('#update_form #name').val(data.name);
                    $('#update_form #slug').val(data.slug);

                    $('#update_modal').modal({
                        keyboard: false,
                        backdrop: 'static',
                    });
                    $('#update_modal .modal-title').html(
                        '<i class="fas fa-edit text-white"></i> <span>Edit ' + data.name + '</span>');
                    $('#update_modal #update-btn').text('Update');

                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        }
    });

    $(document).on('click', '#update-btn', function () {
        let form = document.getElementById('update_form');
        let formData = new FormData(form);
        let url = "{{route('menu.module.permission.update')}}";
        let id = $('#update_id').val();
        let method = 'update';
        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function(){
                $('#update-btn').addClass('spinner spinner-white spinner-right');
            },
            complete: function(){
                $('#update-btn').removeClass('spinner spinner-white spinner-right');
            },
            success: function (data) {
                $('#update_form').find('.is-invalid').removeClass('is-invalid');
                $('#update_form').find('.error').remove();
                if (data.status == false) {
                    $.each(data.errors, function (key, value) {
                        $('#update_form select#' + key).parent().addClass('is-invalid');
                        $('#update_form #' + key).parent().append(
                            '<small class="error text-danger">' + value + '</small>');
                    });
                } else {
                    notification(data.status, data.message);
                    if (data.status == 'success') {
                        table.ajax.reload(null, false);
                        $('#update_modal').modal('hide');
                    }
                }
            },
            error: function (xhr, ajaxOption, thrownError) {
                console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
            }
        });
    });

    $(document).on('click', '.delete_data', function () {
        let id    = $(this).data('id');
        let name  = $(this).data('name');
        let row   = table.row($(this).parent('tr'));
        let url   = "{{ route('menu.module.permission.delete') }}";
        delete_data(id, url, table, row, name);
    });

    function multi_delete(){
        let ids = [];
        let rows;
        $('.select_data:checked').each(function(){
            ids.push($(this).val());
            rows = table.rows($('.select_data:checked').parents('tr'));
        });
        if(ids.length == 0){
            Swal.fire({
                type:'error',
                title:'Error',
                text:'Please checked at least one row of table!',
                icon: 'warning',
            });
        }else{
            let url = "{{route('menu.module.permission.bulk.delete')}}";
            bulk_delete(ids,url,table,rows);
        }
    }

    var count = 1;
    function dynamic_permission_field(row){
        html = ` <tr>
                    <td>
                        <input type="text" name="permission[`+row+`][name]" id="permission_`+row+`_name" 
                        onkeyup="url_generator(this.value,'permission_`+row+`_slug')" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="permission[`+row+`][slug]" id="permission_`+row+`_slug" class="form-control">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm btn-elevate btn-icon remove_permission" data-toggle="tooltip" data-theme="dark" title="Remove">
                        <i class="fas fa-minus-square"></i>
                        </button>
                    </td>
                </tr>`;
        $('#permission-table tbody').append(html);
    }

    $(document).on('click','#add_permission',function(){
        count++;
        dynamic_permission_field(count);
    });
    $(document).on('click','.remove_permission',function(){
        count--;
        $(this).closest('tr').remove();
    });


});

function url_generator(input_value, output_id){
    var value = input_value.toLowerCase().trim();
    var str = value.replace(/ +(?= )/g,'');
    var name = str.split(' ').join('-');
    $('#'+output_id).val(name);
}

</script>
@endpush