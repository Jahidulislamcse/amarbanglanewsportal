<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="GeniusOcean">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{__('Amar Bangla')}}</title>

	<link rel="shortcut icon" href="{{asset('assets/images/'.$gs->favicon)}}" type="image/x-icon">

	<link href="{{asset('assets/admin/css/bootstrap.min.css')}}" rel="stylesheet" />
	<link rel="stylesheet" href="{{asset('assets/admin/css/fontawesome.css')}}">
	<link rel="stylesheet" href="{{asset('assets/admin/css/icofont.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/admin/css/jquery-ui.css')}}">

	<link href="{{asset('assets/admin/plugins/fullside-menu/css/dark-side-style.css')}}" rel="stylesheet" />
	<link href="{{asset('assets/admin/plugins/fullside-menu/waves.min.css')}}" rel="stylesheet" />

	<link href="{{asset('assets/admin/css/plugin.css')}}" rel="stylesheet" />
	<link href="{{asset('assets/admin/css/jquery.tagit.css')}}" rel="stylesheet"/>
	<link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-coloroicker.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/admin/css/datetimepicker.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-tagsinput.css') }}">
	<link href="{{asset('assets/admin/css/tagify.css')}}" rel="stylesheet"/>

	@if(DB::table('admin_languages')->where('is_default','=',1)->first()->rtl == 1)
	<link href="{{asset('assets/admin/css/rtl/style.css')}}" rel="stylesheet"/>
	<link href="{{asset('assets/admin/css/rtl/custom.css')}}" rel="stylesheet"/>
	<link href="{{asset('assets/admin/css/rtl/responsive.css')}}" rel="stylesheet" />
	<link href="{{asset('assets/admin/css/common.css')}}" rel="stylesheet" />
	@else
	<link href="{{asset('assets/admin/css/style.css')}}" rel="stylesheet"/>
	<link href="{{asset('assets/admin/css/custom.css')}}" rel="stylesheet"/>
	<link href="{{asset('assets/admin/css/responsive.css')}}" rel="stylesheet" />
	<link href="{{asset('assets/admin/css/common.css')}}" rel="stylesheet" />
	@endif

<style>
body{
	background:#f5f7fb;
}
html, body{
    height:100%;
    overflow:hidden;  
}

body{
    background:#f5f7fb;
    display:flex;
    flex-direction:column;
}

.app-header{
	height:64px;
	background:#fff;
	display:flex;
	align-items:center;
	justify-content:space-between;
	padding:0 18px;
	border-bottom:1px solid #e9eef5;
	position:sticky;
	top:0;
	z-index:1000;
}

.header-left{
	display:flex;
	align-items:center;
	gap:12px;
}

.policy-link{
    color:#2563eb !important;
    font-weight:600;
    text-decoration:underline;
    cursor:pointer;
}

.policy-modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.65);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:99999;
}

.policy-container{
    width:90%;
    max-width:900px;
    max-height:90vh;
    background:#fff;
    border-radius:14px;
    overflow:hidden;
    display:flex;
    flex-direction:column;
    animation:fadeIn .3s ease;
}

.policy-header{
    padding:16px 20px;
    background:#111827;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;   
    position:relative;
}
.content > .content-area{
    min-height:100%;
}
.policy-header h3{
    margin:0;
    font-size:20px;
    color:#ffffff;            
    text-align:center;
}

.policy-header span{
    position:absolute;
    right:18px;               
    cursor:pointer;
    font-size:18px;
    color:#ffffff;
}

.policy-body{
    padding:24px 28px;
    overflow-y:auto;
    flex:1;
    line-height:1.9;
    font-size:15px;
    color:#333;
    text-align:justify;
}

.policy-body h2{
    margin:26px 0 12px;
    font-size:18px;
    color:#111827;
}

.policy-body p{
    margin-bottom:14px;
}

.policy-body ul{
    margin:12px 0 18px 22px;
}

.policy-body ul li{
    margin-bottom:8px;
}

.policy-table{
    width:100%;
    border-collapse:collapse;
    margin:16px 0;
}

.policy-table th,
.policy-table td{
    border:1px solid #ddd;
    padding:10px;
    text-align:center;
}

@keyframes fadeIn{
    from{opacity:0; transform:scale(.96);}
    to{opacity:1; transform:scale(1);}
}

.menu-btn{
	width:42px;
	height:42px;
	border-radius:10px;
	border:1px solid #e6eaf0;
	background:#fff;
	display:flex;
	align-items:center;
	justify-content:center;
	cursor:pointer;
}

.menu-btn span{
	width:20px;
	height:2px;
	background:#111;
	position:relative;
}

.menu-btn span::before,
.menu-btn span::after{
	content:"";
	position:absolute;
	width:20px;
	height:2px;
	background:#111;
	left:0;
}

.menu-btn span::before{ top:-6px; }
.menu-btn span::after{ top:6px; }

.header-right{
	display:flex;
	align-items:center;
	gap:14px;
}

.header-right a{
	text-decoration:none;
	color:#333;
	font-size:14px;
}

.view-site{
	padding:8px 12px;
	border-radius:8px;
	background:#f3f6fb;
	display:flex;
	align-items:center;
	gap:6px;
}

.user-box{
	position:relative;
	cursor:pointer;
	display:flex;
	align-items:center;
	gap:6px;
}

.user-box img{
	width:38px;
	height:38px;
	border-radius:50%;
	object-fit:cover;
}

.user-box .dropdown-icon{
	font-size:12px;
	color:#555;
	transition:0.3s;
}

.user-box.active .dropdown-icon{
	transform:rotate(180deg);
}

.dropdown{
	position:absolute;
	right:0;
	top:50px;
	width:180px;
	background:#fff;
	border-radius:10px;
	box-shadow:0 10px 25px rgba(0,0,0,0.1);
	display:none;
	z-index:2000;
	overflow:hidden;
}

.dropdown a{
	display:block;
	padding:10px 12px;
	font-size:14px;
	color:#333;
	border-bottom:1px solid #f1f1f1;
	text-decoration:none;
}

.dropdown a:hover{
	background:#f5f7fb;
}

.app-wrapper{
    display:flex;
    height:calc(100vh - 64px); 
}
.sidebar{
	width:250px;
	background:#111827;
	position:relative; 
	left:0;
	height:100%;
	overflow-y:auto;
	transition:0.3s;
	margin-bottom:30px;
}

.sidebar ul{
	list-style:none;
	padding:15px;
}

.sidebar ul li a{
	display:flex;
	align-items:center;
	gap:10px;
	padding:12px 14px;
	border-radius:10px;
	color:#cbd5e1;
	text-decoration:none;
}

.sidebar ul li a:hover{
	background:#1f2937;
	color:#fff;
}

.content{
    flex:1;
    padding:20px;
    overflow-y:auto;            
    height:100%;
}

.overlay{
    position:fixed;
    inset:0;
    display:none;
    z-index:999;
    pointer-events:none;
}

.overlay.active{
    display:block;
    pointer-events:none;
}

@media(max-width:768px){

	.sidebar{
		left:-260px;
		z-index:1000;
	}

	.sidebar.active{
		left:0;
	}

	.content{
		margin-left:0;
	}

	.overlay.active{
		display:block;
	}

	.header-right a{
		display:block;
		font-size:13px;
	}

}
.app-footer{
    background:#111827;   
    color:#ffffff;        
    border-top:1px solid #0f172a;
    padding:15px;
    text-align:center;
    font-size:14px;
    position:sticky;
    bottom:0;
    z-index:5;
}

.app-footer a{
    color:#60a5fa;       
    font-weight:600;
}

.policy-modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.6);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:5000;
}

.policy-box{
    width:90%;
    max-width:900px;
    max-height:85vh;
    overflow:auto;
    background:#fff;
    border-radius:12px;
    padding:25px;
}
.policy-close{
    text-align:right;
    cursor:pointer;
    color:red;
    font-weight:bold;
}
</style>

</head>

<body>

<div class="app-header">

	<div class="header-left">
		<div class="menu-btn" id="menuToggle">
			<span></span>
		</div>

		<!--<a class="logo" href="{{ route('reader.dashboard') }}">-->
		<!--	<img src="{{$gs->logo ? asset('assets/images/logo/'.$gs->logo) : asset('assets/front/images/logo.png')}}" style="height:38px;">-->
		<!--</a>-->
	</div>

	<div class="header-right">

		<a class="view-site" href="{{route('frontend.index')}}">
			<i class="far fa-newspaper"></i> নিউজ পডুন</a>

		<div class="user-box" id="userBox">
            @php $data = auth()->user(); @endphp
        
            <img src="{{ asset('assets/images/admin/'.$data->photo) }}"
                 onerror="this.src='https://www.pngall.com/wp-content/uploads/5/Profile.png'">
        
            <i class="fa fa-chevron-down dropdown-icon"></i>
        
            <div class="dropdown" id="dropdown">
                <a href="{{route('reader.profile')}}">Edit Profile</a>
                <a href="{{route('reader.password')}}">Change Password</a>
                <a href="{{route('user.logout')}}">Logout</a>
            </div>
        </div>

	</div>

</div>

<div class="app-wrapper">

	<div class="sidebar" id="sidebar">
		<ul>
			<li><a href="{{ route('reader.dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
			<li><a href="{{ route('reader.paymentrequest') }}"><i class="fa fa-database"></i> Payment Request</a></li>
		</ul>
	</div>

	<div class="content">
		@yield('content')
	</div>

</div>

<div class="overlay" id="overlay"></div>

<script src="{{asset('assets/admin/js/vendors/jquery-1.12.4.min.js')}}"></script>
<script src="{{asset('assets/admin/js/vendors/vue.js')}}"></script>
<script src="{{asset('assets/admin/js/vendors/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/admin/js/jqueryui.min.js')}}"></script>

<script src="{{asset('assets/admin/plugins/fullside-menu/jquery.slimscroll.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/fullside-menu/waves.min.js')}}"></script>

<script src="{{asset('assets/admin/js/plugin.js')}}"></script>
<script src="{{asset('assets/admin/js/Chart.min.js')}}"></script>
<script src="{{asset('assets/admin/js/tag-it.js')}}"></script>
<script src="{{asset('assets/admin/js/nicEdit.js')}}"></script>
<script src="{{asset('assets/admin/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{asset('assets/admin/js/notify.js') }}"></script>
<script src="{{asset('assets/admin/js/jquery.canvasjs.min.js')}}"></script>
<script src="{{asset('assets/admin/js/load.js')}}"></script>
<script src="{{asset('assets/admin/js/custom.js')}}"></script>
<script src="{{asset('assets/front/js/login.js')}}"></script>
<script src="{{asset('assets/admin/js/myscript.js')}}"></script>
<script src="{{asset('assets/admin/js/bootstrap-tagsinput.js')}}"></script>
<script src="{{asset('assets/admin/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/admin/js/additional-methods.min.js')}}"></script>
<script src="{{asset('assets/admin/js/datetimepicker.js')}}"></script>
<script src="{{asset('assets/admin/js/newspaper.js')}}"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
	$('#report_type').select2({
		placeholder: "Select Report Type",
		allowClear: true,
		width: '100%'
	});
});

const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const menuToggle = document.getElementById('menuToggle');

menuToggle.addEventListener('click', function () {
	sidebar.classList.toggle('active');
	overlay.classList.toggle('active');
});

overlay.addEventListener('click', function () {
	sidebar.classList.remove('active');
	overlay.classList.remove('active');
	$('#dropdown').hide();
});

$('#userBox').on('click', function (e) {
	e.stopPropagation();
	$('#dropdown').toggle();
});

$(document).on('click', function () {
	$('#dropdown').hide();
});
$(document).on('click', '#openPolicy', function(){
    $('#policyModal').fadeIn();
});

$(document).on('click', '#closePolicy', function(){
    $('#policyModal').fadeOut();
});

$(document).on('click', '#policyModal', function(e){
    if(e.target.id === 'policyModal'){
        $('#policyModal').fadeOut();
    }
});
</script>

@yield('scripts')
@include('layouts.readerfooter')
<script>
    document.getElementById('openPolicy').onclick = function () {
        document.getElementById('policyModal').style.display = 'flex';
    };
    
    document.getElementById('closePolicy').onclick = function () {
        document.getElementById('policyModal').style.display = 'none';
    };
    
    window.onclick = function(e){
        if(e.target.id === 'policyModal'){
            document.getElementById('policyModal').style.display = 'none';
        }
    };
</script>
</body>
</html>