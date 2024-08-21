<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    
    @section('title')
    <title>Admin Panel</title>
    @show
    
    <!--
	<link href="assets/css/simplebar.css" rel="stylesheet" />
	<link href="assets/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="assets/css/metisMenu.min.css" rel="stylesheet" />
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/dark-theme.css" />
	<link rel="stylesheet" href="assets/css/semi-dark.css" />
	<link rel="stylesheet" href="assets/css/header-colors.css" />
    -->
    
	<link rel="stylesheet" href="{{ asset('/assets/css/all.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/css/chosen.min.css') }}" />    
    <link rel="stylesheet" href="{{ asset('/font-awesome-4.7.0/css/font-awesome.min.css') }}" />    
    
	<link rel="stylesheet" href="{{ asset('/assets/css/style.css?t='.time()) }}" />
    
    <script>window.Laravel = <?= json_encode(['csrfToken' => csrf_token()]) ?></script>
    
    @stack('styles')
</head>

@section('body')
<body style="overflow: auto;">
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
		<div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header">
				<div>
					<h4 class="logo-text">Admin Panel</h4>
				</div>
				<div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
				</div>
			</div>
			<!--navigation-->
			<ul class="metismenu" id="menu">
				<li>
					<a href="{{ route('apis.index') }}" aria-expanded="false" style="position: static;">
						Apis
                        <div onclick="event.preventDefault(); return false;" class="has-arrow" style="right: 0px; position: absolute;width: 40px; text-align: center;">&nbsp;</div>
					</a>
                    @if(isset($apis))
                    <ul>
                    @foreach($apis as $api)
                    <li>
                    <a href="{{ route('apis.skins', ['id' => $api->id]) }}">{{ $api->name }}</a>
                    </li>
                    @endforeach
                    </ul>
                    @endif
				</li>
				<li>
					<a href="{{ route('skins.index') }}">
						All content
					</a>
				</li>
				<li>
					<a href="{{ route('helps.index') }}">
						Helps
					</a>
				</li>
				<li>
					<a href="{{ route('categories.index') }}">
						Categories
					</a>
				</li>
				<li>
					<a href="{{ route('languages.index') }}">
						Languages
					</a>
				</li>
				<li>
					<a href="{{ route('setting.index') }}">
						Settings
					</a>
				</li>
			</ul>
			<!--end navigation-->
		</div>
		<!--end sidebar wrapper -->
		<!--start header -->
		<header>
			<div class="topbar d-flex align-items-center">
				<nav class="navbar navbar-expand" style="width: 100%;">
					<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
					</div>
					<div style="margin-left: 6px;margin-right: 12px;">
						<div>
							<h5>@section('title_header')@show</h5>
						</div>
					</div>
                    
                    <div style="width: 100%;white-space: nowrap;">
                    <div style="">
                    @if(env('APP_DEBUG'))
                    <div style="margin-left: 6px;float: right;">
					  <div><h6 style="color: #20B2AA!important;">Debag TRUE</h6></div>
					</div>
                    @endif
                            @if(!env('ACCESS_IP'))
                    <div style="margin-left: 6px;float: right;">
					  <div><h6 style="color: #20B2AA!important;">IP not blocked</h6></div>
					</div>
                    @endif
                    </div>
                    </div>
                    
					<div class="top-menu ms-auto" >
						<ul class="navbar-nav align-items-center">
							<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="{{ route('auth.logout') }}">
								<h6 style="">Logout</h6>
                                </a>
								<div class="dropdown-menu dropdown-menu-end">
									<div class="row row-cols-3 g-3 p-3">
										
									</div>
								</div>
							</li>
							<li class="nav-item dropdown dropdown-large" style="display: none!important;">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
									</a>
									<div class="header-notifications-list">
										
									</div>
									<a href="javascript:;">
									</a>
								</div>
							</li>
							<li class="nav-item dropdown dropdown-large" style="display: none!important;">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
									</a>
									<div class="header-message-list">
									</div>
									<a href="javascript:;">
									</a>
								</div>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</header>
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
				@section('page_content')
                
                @show 
			</div>
		</div>
		<!--end page wrapper -->
        
        <footer class="page-footer">
			<p class="mb-0">&nbsp;</p>
		</footer>
	</div>
	<!--end wrapper-->
    
   
</body>
@show 
    <!--
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/simplebar.min.js"></script>
	<script src="assets/js/metisMenu.min.js"></script>
	<script src="assets/js/perfect-scrollbar.js"></script>
	<script src="assets/js/app.js"></script>
    -->
    
	<script src="{{ asset('/assets/js/all.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/js/chosen.jquery.min.js') }}"></script>
    
	<script src="{{ asset('/assets/js/scripts.js?t='.time()) }}"></script>
    
    @stack('scripts')
</html>