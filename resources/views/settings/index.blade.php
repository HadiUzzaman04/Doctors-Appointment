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
            </div>
        </div>
        <!--end::Notice-->
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 mb-5">
                      <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-general-tab" data-toggle="pill" href="#v-pills-general" role="tab" aria-controls="v-pills-general" aria-selected="true">
                            <i class="fas fa-sliders-h mr-2"></i> General Setting
                        </a>
                        <a class="nav-link" id="v-pills-mail-tab" data-toggle="pill" href="#v-pills-mail" role="tab" aria-controls="v-pills-mail" aria-selected="false">
                            <i class="far fa-envelope mr-2"></i> SMTP Settings
                        </a>
                      </div>
                    </div>
                    <div class="col-md-10">
                      <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-general" role="tabpanel" aria-labelledby="v-pills-general-tab">
                            <form id="general-form" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="col-12">
                                    <x-form.textbox labelName="Title" name="title" value="{{ config('settings.title')}}" required="required" col="col-md-6" placeholder="Enter title"/>
                                    <x-form.textbox labelName="Email" name="email" value="{{ config('settings.email')}}"  col="col-md-6" placeholder="Enter email"/>
                                    <x-form.textbox labelName="Contact No." name="contact_no" value="{{ config('settings.contact_no')}}" required="required" col="col-md-6" placeholder="Enter contact no"/>
                                    <x-form.textarea labelName="Address" name="address" value="{{ config('settings.address')}}" required="required" col="col-md-6" placeholder="Enter title"/>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group col-md-6 text-center">
                                                <label for="logo" class="form-control-label">Logo</label> 
                                                <div class="col=md-12 px-0  text-center">
                                                    <div id="site_logo">
                                    
                                                    </div>
                                                </div>
                                                <div class="text-center"><span class="text-muted">Maximum Allowed File Size 2MB and Format PNG</span></div>
                                                <input type="hidden" name="old_logo" value="{{ config('settings.logo')}}">
                                            </div>
                                            <div class="form-group col-md-6 text-center">
                                                <label for="favicon" class="form-control-label">Favicon</label>
                                                <div class="col=md-12 px-0  text-center">
                                                    <div id="site_favicon">
                                    
                                                    </div>
                                                </div>
                                                <div class="text-center"><span class="text-muted">Maximum Allowed File Size 2MB and Format PNG</span></div>
                                                <input type="hidden" name="old_favicon" value="{{ config('settings.favicon')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <x-form.textbox labelName="Currency Code" name="currency_code" value="{{ config('settings.currency_code')}}" required="required" col="col-md-6" placeholder="Enter currency code"/>
                                    <x-form.textbox labelName="Currency Symbol" name="currency_symbol" value="{{ config('settings.currency_symbol')}}" required="required" col="col-md-6" placeholder="Enter currency symbol"/>
                                    <div class="form-group col-md-6 required">
                                        <label>Currency Position</label>
                                        <div class="radio-inline">
                                            <label class="radio">
                                            <input type="radio" {{ config('settings.currency_position') == '1' ? 'checked' : ''}} name="currency_position" id="currency_position" value="1">
                                            <span></span>Prefix</label>
                                            <label class="radio">
                                            <input type="radio" name="currency_position" id="currency_position" {{ config('settings.currency_position') == '2' ? 'checked' : 'checked' }}  value="2">
                                            <span></span>Suffix</label>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Time Zone</label>
                                        <select name="timezone" id="timezone" class="selectpicker form-control" data-live-search="true" title="Select TimeZone">
                                            <option value="">Select Please</option>
                                            @foreach($zones_array as $zone)
                                            <option value="{{$zone['zone']}}" {{ config('settings.timezone') == $zone['zone'] ? 'selected' : ''}}>{{$zone['diff_from_GMT'] . ' - ' . $zone['zone']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Date Format</label>
                                        <select name="date_format" id="date_format" class="selectpicker form-control" data-live-search="true" title="Select Date Format">
                                            <option value="">Select Please</option>
                                            <option value="F j, Y" {{ config('settings.date_format') == "F j, Y" ? 'selected' : ''}}>{{ date('F j, Y') }}</option>
                                            <option value="M j, Y" {{ config('settings.date_format') == "M j, Y" ? 'selected' : ''}}>{{ date('M j, Y') }}</option>
                                            <option value="j F, Y" {{ config('settings.date_format') == "j F, Y" ? 'selected' : ''}}>{{ date('j F, Y') }}</option>
                                            <option value="j M, Y" {{ config('settings.date_format') == "j M, Y" ? 'selected' : ''}}>{{ date('j M, Y') }}</option>
                                            <option value="Y-m-d" {{ config('settings.date_format') == "Y-m-d" ? 'selected' : ''}}>{{ date('Y-m-d') }}</option>
                                            <option value="Y-M-d" {{ config('settings.date_format') == "Y-M-d" ? 'selected' : ''}}>{{ date('Y-M-d') }}</option>
                                            <option value="Y/m/d" {{ config('settings.date_format') == "Y/m/d" ? 'selected' : ''}}>{{ date('Y/m/d') }}</option>
                                            <option value="m/d/Y" {{ config('settings.date_format') == "m/d/Y" ? 'selected' : ''}}>{{ date('m/d/Y') }}</option>
                                            <option value="d/m/Y" {{ config('settings.date_format') == "d/m/Y" ? 'selected' : ''}}>{{ date('d/m/Y') }}</option>
                                            <option value="d.m.Y" {{ config('settings.date_format') == "d.m.Y" ? 'selected' : ''}}>{{ date('d.m.Y') }}</option>
                                            <option value="d-m-Y" {{ config('settings.date_format') == "d-m-Y" ? 'selected' : ''}}>{{ date('d-m-Y') }}</option>
                                            <option value="d-M-Y" {{ config('settings.date_format') == "d-M-Y" ? 'selected' : ''}}>{{ date('d-M-Y') }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <button type="reset" class="btn btn-danger btn-sm">Reset</button>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="save_data('general')" id="general-save-btn">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="v-pills-mail" role="tabpanel" aria-labelledby="v-pills-mail-tab">
                            <form id="mail-form" method="POST">
                                @csrf
                                <div class="col-12">
                                    <div class="form-group col-md-6 required">
                                        <label for="mail_mailer">Mail Driver (Protocol)</label>
                                        <select name="mail_mailer" id="mail_mailer" class="selectpicker form-control" data-live-search="true" title="Select Driver">
                                            <option value="">Select Please</option>
                                            @foreach(MAIL_MAILER as $driver)
                                            <option value="{{$driver}}" {{ (config('settings.mail_mailer') ? config('settings.mail_mailer') : env('MAIL_MAILER')) == $driver ? 'selected' : ''}}>{{$driver}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <x-form.textbox labelName="Host Name" name="mail_host" value="{{ config('settings.mail_host') ? config('settings.mail_host') : env('MAIL_HOST')}}" required="required" col="col-md-6" placeholder="Enter host name"/>
                                    <x-form.textbox labelName="Mail Address" name="mail_username" value="{{ config('settings.mail_username') ? config('settings.mail_username') : env('MAIL_USERNAME')}}" required="required" col="col-md-6" placeholder="Enter mail address"/>
                                    <x-form.textbox labelName="Password" name="mail_password" value="{{ config('settings.mail_password') ? config('settings.mail_password') : env('MAIL_PASSWORD') }}" required="required" col="col-md-6" placeholder="Enter password"/>
                                    <x-form.textbox labelName="Mail From Name" name="mail_from_name" value="{{ config('settings.mail_from_name') ? config('settings.mail_from_name') : env('MAIL_FROM_NAME') }}" required="required" col="col-md-6" placeholder="Enter mail from name"/>
                                    <x-form.textbox labelName="Port" name="mail_port" value="{{ config('settings.mail_port') ? config('settings.mail_port') : env('MAIL_PORT')}}" required="required" col="col-md-6" placeholder="Enter mail port number"/>
                                    <div class="form-group col-md-6 required">
                                        <label for="mail_encryption">Encryption</label>
                                        <select name="mail_encryption" id="mail_encryption" class="selectpicker form-control" data-live-search="true" title="Select Encryption">
                                            <option value="">Select Please</option>
                                            @foreach(MAIL_ENCRYPTION as $key => $encryption)
                                            <option value="{{$encryption}}" {{ (config('settings.mail_encryption') ? config('settings.mail_encryption') : env('MAIL_ENCRYPTION')) == $encryption ? 'selected' : ''}}>{{$key}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <button type="reset" class="btn btn-danger btn-sm">Reset</button>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="save_data('mail')" id="mail-save-btn">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>
@endsection

@push('scripts')
<script src="js/spartan-multi-image-picker.min.js"></script>
<script>
$(document).ready(function(){
   

    $("#site_logo").spartanMultiImagePicker({
        fieldName:        'logo',
        maxCount: 1,
        rowHeight:        '200px',
        groupClassName:   'col-md-12 col-sm-12 col-xs-12',
        maxFileSize:      '',
        dropFileLabel : "Drop Here",
        allowedExt: 'png|jpg|jpeg|gif',
        onExtensionErr : function(index, file){
            Swal.fire({icon: 'error',title: 'Oops...',text: 'Only png,jpg,jpeg,gif file format allowed!'});
        },
    });
    $("#site_favicon").spartanMultiImagePicker({
        fieldName:        'favicon',
        maxCount: 1,
        rowHeight:        '200px',
        groupClassName:   'col-md-12 col-sm-12 col-xs-12',
        maxFileSize:      '',
        dropFileLabel : "Drop Here",
        allowedExt: 'png',
        onExtensionErr : function(index, file){
            Swal.fire({icon: 'error',title: 'Oops...',text: 'Only png file format allowed!'});
        },

    });

    $("input[name='logo'], input[name='favicon']").prop('required',true);

    $('.remove-files').on('click', function(){
        $(this).parents(".col-md-12").remove();
    });


    @if(config('settings.logo'))
    $('#site_logo img').css('display','none');
    $('#site_logo .spartan_remove_row').css('display','none');
    $('#site_logo .img_').css('display','block');
    $('#site_logo .img_').attr('src',"{{ asset('storage/'.LOGO_PATH.config('settings.logo'))}}");
    @endif

    @if(config('settings.favicon'))
    $('#site_favicon img').css('display','none');
    $('#site_favicon .spartan_remove_row').css('display','none');
    $('#site_favicon .img_').css('display','block');
    $('#site_favicon .img_').attr('src',"{{ asset('storage/'.LOGO_PATH.config('settings.favicon'))}}");
    @endif
});



function save_data(form_id) {
    let form = document.getElementById(form_id+'-form');
    let formData = new FormData(form);
    let url;
    if(form_id == 'general'){
        url = "{{route('general.setting')}}";
    }else{
        url = "{{route('mail.setting')}}"
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
            $('#'+form_id+'-save-btn').addClass('spinner spinner-white spinner-right');
        },
        complete: function(){
            $('#'+form_id+'-save-btn').removeClass('spinner spinner-white spinner-right');
        },
        success: function (data) {
            $('#'+form_id+'-form').find('.is-invalid').removeClass('is-invalid');
            $('#'+form_id+'-form').find('.error').remove();
            if (data.status == false) {
                $.each(data.errors, function (key, value) {
                    $('#'+form_id+'-form input#' + key).addClass('is-invalid');
                    $('#'+form_id+'-form textarea#' + key).addClass('is-invalid');
                    $('#'+form_id+'-form select#' + key).parent().addClass('is-invalid');
                    $('#'+form_id+'-form #' + key).parent().append(
                    '<small class="error text-danger">' + value + '</small>');
                });
            } else {
                notification(data.status, data.message);
            }
        },
        error: function (xhr, ajaxOption, thrownError) {
            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
        }
    });
}
</script>
@endpush