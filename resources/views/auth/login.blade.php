<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <base href="{{ asset('/') }}" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('settings.title') ? config('settings.title') : config('app.name', 'Laravel') }} - Login</title>
		<link rel="icon" type="image/png" href="{{ 'storage/'.LOGO_PATH.config('settings.favicon')}}">
		<link rel="shortcut icon" href="{{  'storage/'.LOGO_PATH.config('settings.favicon')}}" />
		<link rel="stylesheet" href="fonts/font-awesome/css/font-awesome.min.css">
		<link href="css/style.bundle.css" rel="stylesheet" type="text/css" />
		<style>
			/* :: 4.0 Preloader Area CSS */
			#preloader {
			  overflow: hidden;
			  height: 100%;
			  left: 0;
			  position: fixed;
			  top: 0;
			  width: 100%;
			  z-index: 100000000;
			  background-color: #fff;
			  display: table;
			}
		
			#preloader #loading {
			  display: table-cell;
			  vertical-align: middle;
			  text-align: center;
			}
		
			</style>
</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
		<div id="preloader">
            <div id="loading">
                <i class="fa fa-spinner fa-spin fa-3x fa-fw text-primary" aria-hidden="true" style="font-size: 80px;"></i>
            </div>
        </div>
		<div class="container-fluid m-0 p-0">
			<div class="row m-0 p-0">
				<div class="col-md-8 d-none d-lg-block d-xl-block" style="background-image: url({{ asset('images/login-bg.jpg') }});background-position: center;
				background-repeat: no-repeat;
				background-size: cover;">
				</div>
				<div class="col-md-4 col-sm-12 bg-white d-flex justify-content-center align-items-center shadow" style="min-height: 100vh;">
					<div class="card w-100" style="border: 0;">
						<div class="card-body">
							<div class="text-center">
								@if (config('settings.logo'))
						<a href="{{ url('/') }}">
							<img src="{{ asset('storage/'.LOGO_PATH.config('settings.logo'))}}"  alt="Logo" style="max-width: 150px;" />
						</a>
						@else
						<h3 class="text-white">{{ config('settings.title') ? config('settings.title') : env('APP_NAME') }}</h3>
						@endif
							</div>
							<div class="py-5 text-center">
								<h2 class="font-weight-bolder">Sign In</h2>
								<p class="text-muted font-weight-bolder">Enter your username and password</p>
							</div>
							<div style="width: 80%;margin:0 auto;">
								@if (session('error'))
								<div class="alert alert-custom alert-notice alert-light-danger fade show mb-5" role="alert">
									<div class="alert-icon">
										<i class="flaticon-warning"></i>
									</div>
									<div class="alert-text">{{ session('error') }}</div>
									<div class="alert-close">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">
												<i class="ki ki-close"></i>
											</span>
										</button>
									</div>
								</div>
								@endif
								<form class="form" action="{{ url('login') }}" method="POST">
									@csrf
									<div class="form-group py-3 m-0">
										<input type="text" class="form-control placeholder-dark-75 @error('username') is-invalid @enderror"  name="username" value="{{ old('username') }}" placeholder="Username" autocomplete="off" />
										@error('username')
											<span class="invalid-feedback" role="alert">
												<strong>{{ $message }}</strong>
											</span>
										@enderror
									</div>
									<div class="form-group py-3">
										<div class="input-group">
											<input type="password" class="form-control placeholder-dark-75 @error('password') is-invalid @enderror" name="password" id="password" placeholder="Password">
											<div class="input-group-prepend">
												<span class="input-group-text bg-primary" style="border-top-right-radius: 0.42rem;border-bottom-right-radius: 0.42rem;border:0;">
													<i class="fa fa-eye toggle-password text-white" toggle="#password" style="cursor: pointer;"></i>
												</span>
											</div>
											@error('password')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
										
									</div>
									<div class="form-group d-flex flex-wrap justify-content-between align-items-center mt-3">
										<div class="checkbox-inline">
											<label class="checkbox checkbox-outline m-0 text-muted">
											<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
											<span></span>Remember me</label>
										</div>
										
									</div>
									<div class="form-group d-flex flex-wrap justify-content-center align-items-center mt-2">
										<button type="submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 w-100"><i class="fa fa-sign-in"></i> Sign In</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>

        <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
        <script>
			var $window = $(window);

			// :: Preloader Active Code
			$window.on('load', function () {
				$('#preloader').fadeOut('slow', function () {
					$(this).remove();
				});
			});
            $(document).ready(function () {
                //Password Show/Hide
                $(".toggle-password").click(function () {
                    $(this).toggleClass("fa-eye fa-eye-slash");
                    var input = $($(this).attr("toggle"));
                    if (input.attr("type") == "password") {
                        input.attr("type", "text");
                    } else {
                        input.attr("type", "password");
                    }
                });
            });
        </script>
	</body>
	<!--end::Body-->
</html>