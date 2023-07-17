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
                            <i class="far fa-id-badge mr-2"></i> Update Profile
                        </a>
                        <a class="nav-link" id="v-pills-mail-tab" data-toggle="pill" href="#v-pills-mail" role="tab" aria-controls="v-pills-mail" aria-selected="false">
                            <i class="fas fa-key mr-2"></i> Change Password
                        </a>
                      </div>
                    </div>
                    <div class="col-md-10">
                      <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-general" role="tabpanel" aria-labelledby="v-pills-general-tab">
                            <form id="profile-form" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="row">
                                            <x-form.textbox labelName="Name" name="name" value="{{ Auth::user()->name }}" required="required" col="col-md-6" placeholder="Enter name"/>
                                                <div class="form-group col-md-6">
                                                    <label for="">Username</label>
                                                    <input type="text" class="form-control" value="{{ Auth::user()->username }}" readonly/>
                                                </div>
                                                <x-form.textbox labelName="Phone No." name="phone" value="{{ Auth::user()->phone }}" required="required" col="col-md-6" placeholder="Enter phone number"/>
                                                <x-form.textbox labelName="Email" type="email" name="email" value="{{ Auth::user()->email }}" col="col-md-6" placeholder="Enter email"/>
                                                <x-form.selectbox labelName="Gender" name="gender" required="required" col="col-md-6" class="selectpicker">
                                                    <option value="1" {{ Auth::user()->gender == '1' ? 'selected' : '' }}>Male</option>
                                                    <option value="2" {{ Auth::user()->gender == '2' ? 'selected' : '' }}>Female</option>
                                                    <option value="3" {{ Auth::user()->gender == '3' ? 'selected' : '' }}>Other</option>
                                                </x-form.selectbox>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group col-md-12">
                                            <label for="">Profile Photo</label>
                                        <div style="width:200px;">
                                            <div class="col=md-12 px-0  text-center">
                                                <div id="avatar">
                                
                                                </div>
                                            </div>
                                            <input type="hidden" name="old_avatar" id="old_avatar" value="{{ Auth::user()->avatar }}">
                                        </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12" style="padding-top:20px;">
                                        <button type="button" class="btn btn-primary btn-sm" id="save-profile" onclick="save_data('profile')"><i class="far fa-save"></i> Save</button>
                                    </div>
                                </div>
                               
                            </form>
                        </div>
                        <div class="tab-pane fade" id="v-pills-mail" role="tabpanel" aria-labelledby="v-pills-mail-tab">
                            <p class="text-danger">**(Password length minimum 8 characters and maximum 30 characters)</p>
                            <form id="password-form" method="POST">
                                @csrf
                                <div class="col-md-6 form-group required">
                                    <label for="site_title">Current Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control bg-brand" name="current_password" id="current_password">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-primary" style="border-top-right-radius: 0.42rem;border-bottom-right-radius: 0.42rem;border:0;">
                                                <i class="fas fa-eye toggle-password text-white" toggle="#current_password" style="cursor: pointer;"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 form-group required">
                                    <label for="site_title">New Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control bg-brand" name="password" id="password">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-warning" id="generate_password" style="border:0;cursor: pointer;" data-toggle="tooltip" data-theme="dark" title="Generate Password">
                                                <i class="fas fa-lock text-white"></i>
                                            </span>
                                        </div>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-primary" style="border-top-right-radius: 0.42rem;border-bottom-right-radius: 0.42rem;border:0;">
                                                <i class="fas fa-eye toggle-password text-white" toggle="#password" style="cursor: pointer;"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-md-6 form-group required">
                                    <label for="site_title">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control bg-brand" name="password_confirmation" id="password_confirmation">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-primary" style="border-top-right-radius: 0.42rem;border-bottom-right-radius: 0.42rem;border:0;">
                                                <i class="fas fa-eye toggle-password text-white" toggle="#password_confirmation" style="cursor: pointer;"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12" style="padding-top:20px;">
                                    <button type="button" class="btn btn-primary btn-sm" id="save-password" onclick="save_data('password')"><i class="far fa-save"></i> Save</button>
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
<script src="js/spartan-multi-image-picker-min.js"></script>
<script>
$(document).ready(function(){
    $(".toggle-password").click(function () {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    $("#avatar").spartanMultiImagePicker({
        fieldName:        'avatar',
        maxCount: 1,
        rowHeight:        '150px',
        groupClassName:   'col-md-12 col-sm-12 col-xs-12',
        maxFileSize:      '',
        dropFileLabel : "Drop Here",
        allowedExt: 'png|jpg|jpeg|gif',
        onExtensionErr : function(index, file){
            Swal.fire({icon: 'error',title: 'Oops...',text: 'Only png,jpg,jpeg,gif file format allowed!'});
        },
    });


    $("input[name='avatar']").prop('required',true);

    $('.remove-files').on('click', function(){
        $(this).parents(".col-md-12").remove();
    });

    @if(Auth::user()->avatar)
    $('#avatar img.spartan_image_placeholder').css('display','none');
    $('#avatar .spartan_remove_row').css('display','none');
    $('#avatar .img_').css('display','block');
    $('#avatar .img_').attr('src',"{{ asset('storage/'.USER_PHOTO_PATH.Auth::user()->avatar)}}");
    @endif
});



function save_data(form_id) {
    let form = document.getElementById(form_id+'-form');
    let formData = new FormData(form);
    let url;
    if(form_id == 'profile'){
        url = "{{route('update.profile')}}";
    }else{
        url = "{{route('update.password')}}"
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
            $('#save-'+form_id).addClass('spinner spinner-white spinner-right');
        },
        complete: function(){
            $('#save-'+form_id).removeClass('spinner spinner-white spinner-right');
        },
        success: function (data) {
            $('#'+form_id+'-form').find('.is-invalid').removeClass('is-invalid');
            $('#'+form_id+'-form').find('.error').remove();
            if (data.status == false) {
                $.each(data.errors, function (key, value) {
                    $('#'+form_id+'-form input#' + key).addClass('is-invalid');
                    if(key == 'current_password' || key == 'password' || key == 'password_confirmation'){
                        $('#'+form_id+'-form #' + key).parents('.form-group').append(
                        '<small class="error text-danger">' + value + '</small>');
                    }else{
                        $('#'+form_id+'-form #' + key).parent().append(
                        '<small class="error text-danger">' + value + '</small>');
                    }
                });
            } else {
                notification(data.status, data.message);
                if(data.status == 'success')
                {
                    window.location.reload();
                }
            }
        },
        error: function (xhr, ajaxOption, thrownError) {
            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
        }
    });
}
/***************************************************
 * * * Begin :: Random Password Genrate Code * * *
 **************************************************/
 const randomFunc = {
    upper : getRandomUpperCase,
    lower : getRandomLowerCase,
    number : getRandomNumber,
    symbol : getRandomSymbol
};


function getRandomUpperCase(){
    return String.fromCharCode(Math.floor(Math.random()*26)+65);
}
function getRandomLowerCase(){
   return String.fromCharCode(Math.floor(Math.random()*26)+97);
}
function getRandomNumber(){
   return String.fromCharCode(Math.floor(Math.random()*10)+48);
}
function getRandomSymbol(){
    var symbol = "!@#$%^&*=~?";
    return symbol[Math.floor(Math.random()*symbol.length)];
}
//generate event
document.getElementById("generate_password").addEventListener('click', () =>{
    const length    = 8;
    const hasUpper  = true;
    const hasLower  = true;
    const hasNumber = true;
    const hasSymbol = true;
    let   password  = generatePassword(hasUpper, hasLower, hasNumber, hasSymbol, length);
    document.getElementById("password").value = password;
    document.getElementById("password_confirmation").value = password;
});
//Generate Password Function
function generatePassword(upper, lower, number, symbol, length){
    let generatedPassword = "";
    const typesCount = upper + lower + number + symbol;
    const typesArr = [{upper}, {lower}, {number}, {symbol}].filter(item => Object.values(item)[0]);
    if(typesCount === 0) {
        return '';
    }
    for(let i=0; i<length; i+=typesCount) {
        typesArr.forEach(type => {
            const funcName = Object.keys(type)[0];
            generatedPassword += randomFunc[funcName]();
        });
    }
    const finalPassword = generatedPassword.slice(0, length);
    return finalPassword;
}
/***************************************************
 * * * End :: Random Password Genrate Code * * *
 **************************************************/
</script>
@endpush