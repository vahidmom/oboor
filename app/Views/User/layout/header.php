 <!DOCTYPE html>
<html lang="fa" dir="rtl" class="rtl">
    <head>
        <title><?= esc($title) ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="قالب مدیریت ایرانی مدیران ">
        <meta name="keywords" content="قالب مدیریت, قالب داشبورد, قالب ادمین, قالب مدیران, قالب مدیریت راستچین , قالب ایرانی مدیریت, پوسته مدیریت, قالب ادمین داشبورد سایت, قالب مدیریتی, مدیران, قالب مدیریت مدیران, پنل مدیریت, پنل مدیریت مدرن, قالب ادمین متریال, قالب مدیریت بوت استرپ, قالب ادمین بوتسترپ, قالب ادمین سایت, پوسته مدیریتی ایرانی, قالب مدیرتی مدیران ایرانی, بهترین قالب مدیریت, قالب مدیریت ریسپانسیو, قالب مدیریت ارزان, قالب admin">
        <meta name="fontiran.com:license" content="NE29X">
        <link rel="shortcut icon" href="assets/images/favicon.png">

        <!-- BEGIN CSS -->
        <link href="<?= base_url('assets/plugins/bootstrap/bootstrap5/css/bootstrap.rtl.min.css') ?>" rel="stylesheet">
        <link href="<?= base_url('assets/plugins/metisMenu/dist/metisMenu.min.css') ?>" rel="stylesheet">
        <link href="<?= base_url('assets/plugins/simple-line-icons/css/simple-line-icons.min.css') ?>" rel="stylesheet">
        <link href="<?= base_url('assets/plugins/font-awesome/css/all.min.css') ?>" rel="stylesheet">
        <link href="<?= base_url('assets/plugins/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css') ?>" rel="stylesheet">
        <link href="<?= base_url('assets/plugins/switchery/dist/switchery.min.css') ?>" rel="stylesheet">
        <link href="<?= base_url('assets/plugins/sweetalert2/dist/sweetalert2.min.css') ?>" rel="stylesheet">
        <link href="<?= base_url('assets/plugins/paper-ripple/dist/paper-ripple.min.css') ?>" rel="stylesheet">
        <link href="<?= base_url('assets/plugins/iCheck/skins/square/_all.css') ?>" rel="stylesheet">
        <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
        <link href="<?= base_url('assets/css/colors.css') ?>" rel="stylesheet">
        <!-- END CSS -->
        
    </head>
    <body class="active-ripple theme-darkpurple fix-header sidebar-extra bg-1">
       
        
        <!-- BEGIN HEADER -->
        <div class="navbar navbar-fixed-top" id="main-navbar">            
            <div class="header-right">
                <a href="dashboard.html" class="logo-con">
                    <img src="<?= base_url('assets/images/logo.png') ?>" class="img-responsive center-block" alt="لوگو قالب مدیران">
                </a>
            </div><!-- /.header-right -->
            <div class="header-left">
                <div class="top-bar">                        
                    <ul class="nav navbar-nav navbar-right">
                        <li>                                
                            <a href="#" class="btn" id="toggle-sidebar">
                                <span class="menu"></span>
                            </a>
                        </li>
                        <li>                                
                            <a href="#" class="btn open" id="toggle-sidebar-top">
                                <i class="icon-user-following"></i>
                            </a>
                        </li>
                        <li>                                
                            <a href="#" class="btn" id="toggle-dark-mode">
                                <i class="icon-bulb"></i>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-left">
                        <li class="dropdown">
                            <a href="#" class="btn" id="toggle-fullscreen">
                                <i class="icon-size-fullscreen"></i>
                            </a>
                        </li>
                        <li class="dropdown dropdown-messages">
                            <a href="#" class="dropdown-toggle btn" data-bs-toggle="dropdown">
                                <i class="icon-envelope"></i>
                                <span class="badge badge-primary">
                                    4
                                </span>
                            </a>
                            <ul class="dropdown-menu has-scrollbar">
                                <li class="dropdown-header clearfix">
                                    <span class="float-start">
                                        <a href="#" rel="tooltip" title="خواندن همه" data-placement="left">
                                            <i class="icon-eye"></i>
                                        </a>
                                        شما 4 پیام تازه دارید.
                                    </span>
                                </li>
                                <li class="dropdown-body">
                                    <ul class="dropdown-menu-list" >
                                        <li class="clearfix">
                                            <a href="#">
                                                <p class="clearfix">
                                                    <strong class="float-start">
                                                        <img src="<?= base_url('assets/images/user/32.png') ?>" class="img-circle" alt="">
                                                        سهراب سپهری
                                                    </strong> 
                                                    <small class="float-end text-muted">
                                                        <i class="icon-clock"></i>
                                                        ده دقیقه پیش
                                                    </small>
                                                </p>
                                                <p>پیام پرمهرتان دریافت شد!</p>
                                            </a>
                                        </li>
                                        <li class="clearfix">
                                            <a href="#">
                                                <p class="clearfix">
                                                    <strong class="float-start">
                                                        <img src="<?= base_url('assets/images/user/32.png') ?>" class="img-circle" alt="">
                                                        شفیعی کدکنی
                                                    </strong> 
                                                    <small class="float-end text-muted">
                                                        <i class="icon-clock"></i>
                                                        سی دقیقه پیش
                                                    </small>
                                                </p>
                                                <p>بسته ارسالی شما به دستم رسید.</p>
                                            </a>
                                        </li>
                                        <li class="clearfix">
                                            <a href="#">
                                                <p class="clearfix">
                                                    <strong class="float-start">
                                                        <img src="<?= base_url('assets/images/user/32.png') ?>" class="img-circle" alt="">
                                                        قیصر امین پور
                                                    </strong> 
                                                    <small class="float-end text-muted">
                                                        <i class="icon-clock"></i>
                                                        یک ساعت پیش
                                                    </small>
                                                </p>
                                                <p>مجموعه آثار بنده را ببینید.</p>
                                            </a>
                                        </li>
                                        <li class="clearfix">
                                            <a href="#">
                                                <p class="clearfix">
                                                    <strong class="float-start">
                                                        <img src="<?= base_url('assets/images/user/32.png') ?>" class="img-circle" alt="">
                                                        مهدی اخوان ثالث
                                                    </strong> 
                                                    <small class="float-end text-muted">
                                                        <i class="icon-clock"></i>
                                                        دو ساعت پیش
                                                    </small>
                                                </p>
                                                <p>با تشکر...</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-footer clearfix">
                                    <a href="#">
                                        <i class="icon-list fa-flip-horizontal"></i>
                                        مشاهده همه پیام ها
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown dropdown-announces">
                            <a href="#" class="dropdown-toggle btn" data-bs-toggle="dropdown">
                                <i class="icon-bell"></i>
                                <span class="badge badge-success">
                                    5
                                </span>
                            </a>
                            <ul class="dropdown-menu has-scrollbar">
                                <li class="dropdown-header clearfix">
                                    <span class="float-start">
                                        <a href="#" rel="tooltip" title="خواندن همه" data-placement="left">
                                            <i class="icon-eye"></i>
                                        </a>
                                        <span>
                                            شما 8 اعلان تازه دارید.
                                        </span>
                                    </span>

                                </li>
                                <li class="dropdown-body">
                                    <ul class="dropdown-menu-list" >
                                        <li class="clearfix">
                                            <a href="#">
                                                <p class="clearfix">
                                                    <strong class="float-start">عباس دوران</strong> 
                                                    <small class="float-end text-muted">
                                                        <i class="icon-clock"></i>
                                                        21:30
                                                    </small>
                                                </p>
                                                <p>بسته ارسالی شما به دستم رسید.</p>
                                            </a>
                                        </li>
                                        <li class="clearfix">
                                            <a href="#">
                                                <p class="clearfix">
                                                    <strong class="float-start">حسن باقری</strong> 
                                                    <small class="float-end text-muted">
                                                        <i class="icon-clock"></i>
                                                        20:20
                                                    </small>
                                                </p>
                                                <p>از محبت شما ممنونم.</p>
                                            </a>
                                        </li>
                                        <li class="clearfix">
                                            <a href="#">
                                                <p class="clearfix">
                                                    <strong class="float-start">مدیر کل</strong> 
                                                    <small class="float-end text-muted">
                                                        <i class="icon-clock"></i>
                                                        19:20
                                                    </small>
                                                </p>
                                                <p>سفارش شما ارسال گردید..</p>
                                            </a>
                                        </li>
                                        <li class="clearfix">
                                            <a href="#">
                                                <p class="clearfix">
                                                    <strong class="float-start">مدیر مالی</strong> 
                                                    <small class="float-end text-muted">
                                                        <i class="icon-clock"></i>
                                                        17:40
                                                    </small>
                                                </p>
                                                <p>درخواست فیش حقوقی</p>
                                            </a>
                                        </li>
                                        <li class="clearfix">
                                            <a href="#">
                                                <p class="clearfix">
                                                    <strong class="float-start">ابراهیم همت</strong> 
                                                    <small class="float-end text-muted">
                                                        <i class="icon-clock"></i>
                                                        15:45
                                                    </small>
                                                </p>
                                                <p>پیام های مرا دنبال کنید.</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-footer clearfix">
                                    <a href="#">
                                        <i class="icon-list fa-flip-horizontal"></i>
                                        مشاهده همه اعلانات  
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown dropdown-user">
                            <a href="#" class="dropdown-toggle dropdown-hover" data-bs-toggle="dropdown">
                                <img src="<?= base_url('assets/images/user/48.png') ?>" alt="عکس پرفایل" class="img-circle img-responsive">
                                <span>حمید آفرینش فر</span>
                                <i class="icon-arrow-down"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="edit_profile.html">
                                        <i class="icon-note"></i>
                                        ویرایش پروفایل
                                    </a>
                                </li>
                                <li>
                                    <a href="change_password.html">
                                        <i class="icon-key"></i>
                                        تغییر رمز عبور
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="chat.html">
                                        <span class="badge badge-primary float-end"> 14 </span>
                                        <i class="icon-envelope"></i>
                                        تیکت های جدید 
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="icon-wallet"></i>
                                        کیف پول
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="login.html">
                                        <i class="icon-power"></i>
                                        خروج
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul><!-- /.navbar-left -->
                </div><!-- /.top-bar -->
            </div><!-- /.header-left -->
        </div><!-- /.navbar -->
        <!-- END HEADER -->
		
		      <!-- BEGIN WRAPPER -->
        <div id="wrapper">