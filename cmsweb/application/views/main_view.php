
<!DOCTYPE html>
<html lang="en">

<!-- begin::Head -->
<head>

    <!--begin::Base Path (base relative path for assets of this page) -->
    <base href="../">

    <!--end::Base Path -->
    <meta charset="utf-8" />
    <title>Agronow | Dashboard</title>
    <meta name="description" content="Dashboard">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name=“robots” content="noimageindex,nofollow,nosnippet,noindex">

    <!--begin::Fonts -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Roboto:400,450,500,600,700","Poppins:400,450,500,600,700",]
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!--end::Fonts -->



    <!--begin::Page Vendors Styles(used by this page) -->
    <link href="<?php echo base_url('assets'); ?>/vendors/custom/fullcalendar/fullcalendar.bundle.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />


    <!--end::Page Vendors Styles -->

    <!--begin:: Global Mandatory Vendors -->
    <link href="<?php echo base_url('assets'); ?>/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" type="text/css" />

    <!--end:: Global Mandatory Vendors -->

    <!--begin:: Global Optional Vendors -->
    <link href="<?php echo base_url('assets'); ?>/vendors/general/tether/dist/css/tether.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/general/select2/dist/css/select2.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/general/ion-rangeslider/css/ion.rangeSlider.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/general/nouislider/distribute/nouislider.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/general/owl.carousel/dist/assets/owl.carousel.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/general/owl.carousel/dist/assets/owl.theme.default.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/general/dropzone/dist/dropzone.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/general/summernote/dist/summernote.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-markdown/css/bootstrap-markdown.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/general/animate.css/animate.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/general/morris.js/morris.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/general/sweetalert2/dist/sweetalert2.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/general/socicon/css/socicon.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/custom/vendors/line-awesome/css/line-awesome.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/custom/vendors/flaticon/flaticon.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/custom/vendors/flaticon2/flaticon.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/vendors/general/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />

    <!--end:: Global Optional Vendors -->

    <!--begin::Global Theme Styles(used by all pages) -->
    <link href="<?php echo base_url('assets'); ?>/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->
    <link href="<?php echo base_url('assets'); ?>/css/skins/header/base/light.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/css/skins/header/menu/light.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/css/skins/brand/light.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets'); ?>/css/skins/aside/light.css" rel="stylesheet" type="text/css" />
    <!--end::Layout Skins -->
    <link rel="shortcut icon" href="<?php echo base_url('assets'); ?>/media/logos/favicon.png" />


    <style>
        /*Highchart*/
        .highcharts-credits{
            display: none;
        }

        /*Subheader Title*/
        .kt-subheader .kt-subheader__main .kt-subheader__title{
            font-weight: 100 !important;
            color: #646c9a !important;
        }

        /*Sidebar scroll*/
        .ps__rail-x,
        .ps__rail-y {
            opacity: 0.6;
        }

        /* This only works with JavaScript, if it's not present, don't show loader */
        .no-js #loader { display: none;  }
        .js #loader { display: block; position: absolute; left: 100px; top: 0; }
        .se-pre-con {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url("<?php echo base_url('assets/media/preloader/set2/64/Preloader_4.gif');?>") center no-repeat #fff;
        }

        .input-hidden{
            height:0;
            width:0;
            visibility: hidden;
            padding:0;
            margin:0;
            float:right;
        }

        /*Aside Default 265px*/
        /*.kt-aside{
            width : 220px ;
        }

        .kt-aside--fixed .kt-wrapper{
            padding-left: 220px ;
        }

        @media (min-width: 1025px){
            .kt-aside--enabled .kt-header.kt-header--fixed{
                left: 220px ;
            }

            .kt-aside--enabled.kt-subheader--fixed .kt-subheader{
                left: 220px ;
            }
        }*/


        .select2-search{
            color: black !important;
        }

        .select2-results__option select2-results__option--highlighted{
            color: red !important;
        }


    </style>

    <!--begin:: Global Mandatory Vendors -->
    <script src="<?php echo base_url('assets'); ?>/vendors/general/jquery/dist/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url('assets'); ?>/vendors/general/popper.js/dist/umd/popper.js" type="text/javascript"></script>
    <script src="<?php echo base_url('assets'); ?>/vendors/general/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url('assets'); ?>/vendors/general/js-cookie/src/js.cookie.js" type="text/javascript"></script>
    <script src="<?php echo base_url('assets'); ?>/vendors/general/moment/min/moment.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url('assets'); ?>/vendors/general/tooltip.js/dist/umd/tooltip.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url('assets'); ?>/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js" type="text/javascript"></script>
    <script src="<?php echo base_url('assets'); ?>/vendors/general/sticky-js/dist/sticky.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url('assets'); ?>/vendors/general/wnumb/wNumb.js" type="text/javascript"></script>
    <!--end:: Global Mandatory Vendors -->


    <script src="<?php echo base_url('assets'); ?>/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>

    <script>
        // jQuery plugin to prevent double submission of forms
        jQuery.fn.preventDoubleSubmission = function() {
            $(this).on('submit',function(e){
                var $form = $(this);

                if ($form.data('submitted') === true) {
                    // Previously submitted - don't submit again
                    e.preventDefault();
                } else {
                    // Mark it so that the next submit can be ignored
                    $form.data('submitted', true);
                }
            });

            // Keep chainability
            return this;
        };
    </script>


    <script>
        //paste this code under head tag or in a seperate js file.
        // Wait for window load
        $(window).on("load", function() {
            // Animate loader off screen
            $(".se-pre-con").fadeOut("slow");;
        });
    </script>

</head>
<!-- end::Head -->

<!-- begin::Body -->
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

<!-- Loader -->
<!--<div class="se-pre-con"></div>-->

<!-- begin:: Page -->

<!-- begin:: Header Mobile -->
<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
    <div class="kt-header-mobile__logo">
        <h4 class="text-light"><?php echo isset($page_name)?$page_name:'A Quick Glance'; ?></h4>
        <!--<a href="<?php /*echo site_url();*/?>">
            <img alt="Logo" src="<?php /*echo base_url('assets'); */?>/media/logos/logo-light.png" />
        </a>-->
    </div>
    <div class="kt-header-mobile__toolbar">
        <button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
<!--        <button class="kt-header-mobile__toggler" id="kt_header_mobile_toggler"><span></span></button>-->
        <button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
    </div>
</div>
<!-- end:: Header Mobile -->

<div class="kt-grid kt-grid--hor kt-grid--root">
    <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

        <!-- begin:: Aside -->
        <button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
        <div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">

            <!-- begin:: Aside -->
            <div class="kt-aside__brand kt-grid__item mb-3 mt-3" id="kt_aside_brand">

                <div class="kt-aside__brand-logo" style="padding-top: 0px">
                    <img alt="Logo" src="<?php echo base_url('assets'); ?>/media/logos/logo-light.png" />
                </div>

                <div class="kt-aside__brand-tools">
                    <!--<button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler">
                        <span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                <path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) "></path>
                                <path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) "></path>
                            </g>
                        </svg></span>
                        <span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero"></path>
                                <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) "></path>
                            </g>
                        </svg></span>
                    </button>-->
                    <!--
                    <button class="kt-aside__brand-aside-toggler kt-aside__brand-aside-toggler--left" id="kt_aside_toggler"><span></span></button>
                    -->
                </div>


            </div>

            <!-- end:: Aside -->

            <!-- begin:: Aside Menu -->
            <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
                <div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">
                    <ul class="kt-menu__nav ">

                        <!-- Dashboard -->
                        <?php if(has_access('dashboard.view',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='dashboard'?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('dashboard'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-chart-bar"></i></span>
                                <span class="kt-menu__link-text">Dashboard</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <!-- Inbox -->
                        <?php if(has_access('inbox.view',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='inbox'?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('inbox'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-inbox"></i></span>
                                <span class="kt-menu__link-text">Inbox</span>
                            </a>
                        </li>
                        <?php endif; ?>
						
						<!-- SEPARATOR -->
                        <li class="kt-menu__section bg-primary">
                            <h4 class="kt-menu__section-text text-light">Laporan</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
						
						<!-- LAPORAN -->
						<?php if(has_access('laporan.classroom_evaluasilv3_entitas',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo (($this->uri->segment(2)=='laporan_evaluasi_lv3_av') AND ($this->uri->segment(3)=='post'))?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('dashboard/laporan_evaluasi_lv3_av'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-chart-bar"></i></span>
                                <span class="kt-menu__link-text">Class Room - Evaluasi Level 3 (Aspect View)</span>
                            </a>
                        </li>
                        <?php endif; ?>
						
						<?php if(has_access('laporan.classroom_evaluasilv3_entitas',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo (($this->uri->segment(2)=='laporan_evaluasi_lv3') AND ($this->uri->segment(3)=='post'))?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('dashboard/laporan_evaluasi_lv3'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-chart-bar"></i></span>
                                <span class="kt-menu__link-text">Class Room - Evaluasi Level 3 (Partisipant View)</span>
                            </a>
                        </li>
                        <?php endif; ?>
						
                        <?php if(has_access('laporan.classroom_evaluasilv3_entitas',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo (($this->uri->segment(2)=='laporan_evaluasi_lv3_cv') AND ($this->uri->segment(3)=='gap'))?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('dashboard/laporan_evaluasi_lv3_cv'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-chart-bar"></i></span>
                                <span class="kt-menu__link-text">Class Room - Evaluasi Level 3 (Class View)</span>
                            </a>
                        </li>
                        <?php endif; ?>
						
                        <!-- SEPARATOR -->
                        <li class="kt-menu__section bg-success">
                            <h4 class="kt-menu__section-text text-light">What's New</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>


                        <!-- Popup -->
                        <?php if(has_access('popup.view',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='popup'?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('popup'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-podcast"></i></span>
                                <span class="kt-menu__link-text">Popup</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <!-- CEO Notes -->
                        <?php if(has_access('ceonotes.view',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='ceo_notes'?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('ceo_notes'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-sticky-note"></i></span>
                                <span class="kt-menu__link-text">CEO Notes</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <!-- BOD Share -->
                        <?php if(has_access('bodshare.view',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='bod_share'?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('bod_share'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-sticky-note"></i></span>
                                <span class="kt-menu__link-text">BOD Share</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <!-- Announcement -->
                        <?php if(has_access('announcement.view',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='announcement'?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('announcement'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-bell"></i></span>
                                <span class="kt-menu__link-text">Announcement</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <!-- News -->
                        <?php if(has_access('news.view',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='news'?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('news'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-newspaper"></i></span>
                                <span class="kt-menu__link-text">News</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <!-- Article -->
                        <?php if(has_access('article.view',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='article'?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('article'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-newspaper"></i></span>
                                <span class="kt-menu__link-text">Article</span>
                            </a>
                        </li>
                        <?php endif; ?>


                        <!-- Forum -->
                        <?php if(has_access('parentmenu.forum',FALSE)): ?>

                        <li class="kt-menu__item <?php echo (($this->uri->segment(1)=='forum')OR($this->uri->segment(1)=='forum_category')OR($this->uri->segment(1)=='forum_category_suggest'))?'kt-menu__item--open':NULL; ?> kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">

                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-icon"><i class="fa fa-users-cog"></i></span>
                                <span class="kt-menu__link-text">Forum</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>

                            <!--Submenu-->
                            <div class="kt-menu__submenu " kt-hidden-height="80" style=""><span class="kt-menu__arrow"></span>

                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">List Forum</span>
                                        </span>
                                    </li>

                                    <?php if(has_access('forum.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='forum'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('forum'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">List Forum</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('forumcat.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='forum_category'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('forum_category'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Kategori</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('forumcatsuggest.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='forum_category_suggest'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('forum_category_suggest'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Usulan Kategori</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                </ul>

                            </div>

                        </li>

                        <?php endif; ?>

                        <!-- Survey -->
                        <?php /* if(has_access('survey.view',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='survey'?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('survey'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-poll"></i></span>
                                <span class="kt-menu__link-text">Survey</span>
                            </a>
                        </li>
                        <?php endif; */ ?>

                        <!-- Kamus Istilah -->
                        <?php if(has_access('kamus.view',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='kamus'?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('kamus'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-book"></i></span>
                                <span class="kt-menu__link-text">Kamus Istilah</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <!-- Kurs -->
                        <?php if(has_access('kurs.view',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='kurs'?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('kurs'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-chart-line"></i></span>
                                <span class="kt-menu__link-text">Kurs</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <!-- Commodity -->
                        <?php if(has_access('commodity.view',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='commodity'?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('commodity'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-chart-line"></i></span>
                                <span class="kt-menu__link-text">Commodity</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <!-- QR Content -->
                        <?php if(has_access('qrcontent.view',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='qr_content'?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('qr_content'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-qrcode"></i></span>
                                <span class="kt-menu__link-text">QR Content</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <!-- Single Page -->
                        <?php if(has_access('page.view',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='single_page'?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('single_page'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-file"></i></span>
                                <span class="kt-menu__link-text">Single Page</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <!-- Quotes -->
                        <?php if(has_access('quotes.view',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='quotes'?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('quotes'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-quote-left"></i></span>
                                <span class="kt-menu__link-text">Quotes</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <!-- Sponsor Ads -->
                        <?php if(has_access('ads.view',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='ads'?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('ads'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-anchor"></i></span>
                                <span class="kt-menu__link-text">Sponsor Ads</span>
                            </a>
                        </li>
                        <?php endif; ?>


                        <!-- SEPARATOR -->
                        <li class="kt-menu__section bg-warning">
                            <h4 class="kt-menu__section-text text-light">Learning Room</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>

                        <!-- Expert Directory -->
                        <?php if(has_access('parentmenu.expert',FALSE)): ?>

                        <li class="kt-menu__item <?php echo (($this->uri->segment(1)=='expert')OR($this->uri->segment(1)=='expert_category')OR($this->uri->segment(1)=='expert_member'))?'kt-menu__item--open':NULL; ?> kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">

                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-icon"><i class="fa fa-book-reader"></i></span>
                                <span class="kt-menu__link-text">Expert Directory</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>

                            <!--Submenu-->
                            <div class="kt-menu__submenu " kt-hidden-height="80" style=""><span class="kt-menu__arrow"></span>

                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">List Expert Directory</span>
                                        </span>
                                    </li>

                                    <?php if(has_access('expertdirectory.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='expert'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('expert'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">List Expert Directory</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('expertcat.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='expert_category'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('expert_category'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Kategori</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('expertmember.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='expert_member'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('expert_member'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Member Expert</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                </ul>

                            </div>

                        </li>

                        <?php endif; ?>

                        <!-- Knowledge Sharing -->
                        <?php if(has_access('parentmenu.knowledge',FALSE)): ?>

                        <li class="kt-menu__item <?php echo (($this->uri->segment(1)=='knowledge_sharing')OR($this->uri->segment(1)=='knowledge_sharing_category')OR($this->uri->segment(1)=='job_title')OR($this->uri->segment(1)=='grade')OR($this->uri->segment(1)=='location')OR($this->uri->segment(1)=='document'))?'kt-menu__item--open':NULL; ?> kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">

                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-icon"><i class="fa fa-share"></i></span>
                                <span class="kt-menu__link-text">Knowledge Management</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>

                            <!--Submenu-->
                            <div class="kt-menu__submenu " kt-hidden-height="80" style=""><span class="kt-menu__arrow"></span>

                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">List Knowledge Management</span>
                                        </span>
                                    </li>

                                    <?php if(has_access('knowledgemanagement.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='knowledge_sharing'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('knowledge_sharing'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">List Knowledge Management</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('knowledgemanagementcat.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='knowledge_sharing_category'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('knowledge_sharing_category'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Kategori</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                </ul>

                            </div>

                        </li>

                        <?php endif; ?>

                        <!-- Digital Library -->
                        <?php if(has_access('parentmenu.digilib',FALSE)): ?>

                        <li class="kt-menu__item <?php echo (($this->uri->segment(1)=='digital_library')OR($this->uri->segment(1)=='digital_library_category')OR($this->uri->segment(1)=='job_title')OR($this->uri->segment(1)=='grade')OR($this->uri->segment(1)=='location')OR($this->uri->segment(1)=='document'))?'kt-menu__item--open':NULL; ?> kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">

                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-icon"><i class="fa fa-address-book"></i></span>
                                <span class="kt-menu__link-text">Digital Library</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>

                            <!--Submenu-->
                            <div class="kt-menu__submenu " kt-hidden-height="80" style=""><span class="kt-menu__arrow"></span>

                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">List Digital Library</span>
                                        </span>
                                    </li>

                                    <?php if(has_access('digitallibrary.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='digital_library'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('digital_library'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">List Digital Library</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('digitallibrarycat.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='digital_library_category'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('digital_library_category'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Kategori</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                </ul>

                            </div>

                        </li>

                        <?php endif; ?>

                        <!-- Class Room -->
                        <?php if(has_access('parentmenu.classroom',FALSE)): ?>

                        <li class="kt-menu__item <?php echo (($this->uri->segment(1)=='classroom')OR($this->uri->segment(1)=='classroom_category')OR($this->uri->segment(1)=='classroom_soal')OR($this->uri->segment(1)=='grade')OR($this->uri->segment(1)=='location')OR($this->uri->segment(1)=='document'))?'kt-menu__item--open':NULL; ?> kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">

                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-icon"><i class="fa fa-chalkboard-teacher"></i></span>
                                <span class="kt-menu__link-text">Class Room</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>

                            <!--Submenu-->
                            <div class="kt-menu__submenu " kt-hidden-height="80" style=""><span class="kt-menu__arrow"></span>

                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">List Class Room</span>
                                        </span>
                                    </li>

                                    <?php if(has_access('classroomcat.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='classroom_category'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('classroom_category'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Kategori</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>


                                    <?php if(has_access('classroom.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='classroom' AND $this->uri->segment(2)!='attendance_scan' ?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('classroom?cr_type=elearning'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">List Class Room</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>


                                    <?php if(has_access('classroom.evaluasilv3_view',FALSE) || $_SESSION['user_level_id']==9): ?>
                                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='classroom' AND $this->uri->segment(2)!='attendance_scan' ?'active':NULL; ?>" aria-haspopup="true">
                                            <a href="<?php echo site_url('classroom/evaluasi_lv3_list'); ?>" class="kt-menu__link ">
                                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                <span class="kt-menu__link-text">Class Room yang Memiliki Evaluasi Level 3</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>


                                    <?php if(has_access('classroomscan.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(2)=='attendance_scan'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('classroom/attendance_scan'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Scan Barcode</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('classroomsoal.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='classroom_soal'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('classroom_soal'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Bank Soal</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('classroom.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='classroom_soal'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('Report_classroom/test_result'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Nilai Kelas</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                         <!--- NPS -->
                                    <!-- KDW 30012024 -->
                                    <!--- 1. Bank Soal -->
                                    <!--- 2. Report -->
                                    <?php if(has_access('classroom.view',FALSE)): ?>
                                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='classroom_soal'?'active':NULL; ?>" aria-haspopup="true">
                                            <a href="<?php echo site_url('classroom/daftar_evaluasi'); ?>" class="kt-menu__link ">
                                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                <span class="kt-menu__link-text">Bank Soal NPS</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if(has_access('classroom.view',FALSE)): ?>
                                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='classroom_soal'?'active':NULL; ?>" aria-haspopup="true">
                                            <a href="<?php echo site_url('classroom/npsreport'); ?>" class="kt-menu__link ">
                                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                <span class="kt-menu__link-text">Laporan NPS</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <!---- END NPS -->


                                </ul>

                            </div>

                        </li>

                        <?php endif; ?>


                        <!-- Kompetensi -->
                        <?php /* if(has_access('parentmenu.kompetensi',FALSE)): ?>

                        <li class="kt-menu__item <?php echo (($this->uri->segment(1)=='kompetensi')OR($this->uri->segment(1)=='kompetensi_category')OR($this->uri->segment(1)=='kompetensi_soal')OR($this->uri->segment(1)=='grade')OR($this->uri->segment(1)=='location')OR($this->uri->segment(1)=='document'))?'kt-menu__item--open':NULL; ?> kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">

                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-icon"><i class="fa fa-chalkboard-teacher"></i></span>
                                <span class="kt-menu__link-text">Kompetensi</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>

                            <!--Submenu-->
                            <div class="kt-menu__submenu " kt-hidden-height="80" style=""><span class="kt-menu__arrow"></span>

                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">List Kompetensi</span>
                                        </span>
                                    </li>

                                    <?php if(has_access('kompetensi.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='kompetensi'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('kompetensi/?year=').date('Y'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">List Kompetensi</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('kompetensicat.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='kompetensi_category'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('kompetensi_category'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Kategori</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('kompetensisoal.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='kompetensi_soal'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('kompetensi_soal'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Bank Soal</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                </ul>

                            </div>

                        </li>

                        <?php endif; */ ?>


                        <!-- Corporate Culture -->
                        <?php /* if(has_access('parentmenu.culture',FALSE)): ?>

                        <li class="kt-menu__item <?php echo (($this->uri->segment(1)=='culture')OR($this->uri->segment(1)=='culture_category')OR($this->uri->segment(1)=='culture_soal')OR($this->uri->segment(1)=='grade')OR($this->uri->segment(1)=='location')OR($this->uri->segment(1)=='document'))?'kt-menu__item--open':NULL; ?> kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">

                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-icon"><i class="fa fa-chalkboard-teacher"></i></span>
                                <span class="kt-menu__link-text">Corporate Culture</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>

                            <!--Submenu-->
                            <div class="kt-menu__submenu " kt-hidden-height="80" style=""><span class="kt-menu__arrow"></span>

                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">List Corporate Culture</span>
                                        </span>
                                    </li>

                                    <?php if(has_access('culturecat.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='culture_category'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('culture_category'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Kategori</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>


                                    <?php if(has_access('culture.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='culture'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('culture'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">List Corporate Culture</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>


                                    <?php if(has_access('culturesoal.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='culture_soal'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('culture_soal'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Bank Soal</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                </ul>

                            </div>

                        </li>

                        <?php endif; */ ?>


                        <!-- Pelatihan -->
                        <?php /* if(has_access('pelatihan.view',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='pelatihan'?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('pelatihan'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-pencil-ruler"></i></span>
                                <span class="kt-menu__link-text">Pelatihan</span>
                            </a>
                        </li>
                        <?php endif; */ ?>



                        <!-- SEPARATOR -->
                        <li class="kt-menu__section bg-primary">
                            <h4 class="kt-menu__section-text text-light">Portal</h4>
                            <i class="kt-menu__section-icon flaticon-more-v2"></i>
                        </li>
						
						<!-- Learning Wallet -->
                        <?php if(has_access('parentmenu.learningwallet',FALSE)): ?>
                        <li class="kt-menu__item <?php echo (($this->uri->segment(1)=='learning_wallet'))?'kt-menu__item--open':NULL; ?> kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">

                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-icon"><i class="fa-solid fa-wallet"></i></span>
                                <span class="kt-menu__link-text">Learning Wallet</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>

                            <!--Submenu-->
                            <div class="kt-menu__submenu " kt-hidden-height="80" style=""><span class="kt-menu__arrow"></span>

                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Learning Wallet</span>
                                        </span>
                                    </li>
									
									<?php if(has_access('learningwallet.konfig_approval',FALSE)): ?>
									<li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='learning_wallet'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('learning_wallet/approval'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Konfigurasi Approval</span>
                                        </a>
                                    </li>
									<?php endif; ?>
									
									<?php if(has_access('learningwallet.pelatihan_view',FALSE)): ?>
									<li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='learning_wallet'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('learning_wallet/pelatihan'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Daftar Pelatihan</span>
                                        </a>
                                    </li>
									<?php endif; ?>
									
									<?php if(has_access('learningwallet.usulan_view',FALSE)): ?>
									<li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='learning_wallet'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('learning_wallet/usulan_pelatihan'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Daftar Usulan Pelatihan</span>
                                        </a>
                                    </li>
									<?php endif; ?>
									
									<?php if(has_access('learningwallet.tracking_penyelenggaraan_view',FALSE)): ?>
									<li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='learning_wallet'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('learning_wallet/tracking_penyelenggaraan'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Tracking Penyelenggaraan Pelatihan</span>
                                        </a>
                                    </li>
									<?php endif; ?>
									
									<?php if(has_access('learningwallet.dashboard',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='learning_wallet'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('learning_wallet/saldo_view'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Saldo Peserta</span>
                                        </a>
                                    </li>
									<?php endif; ?>
									
									<?php if(has_access('learningwallet.dashboard',FALSE)): ?>
									<li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='learning_wallet'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('learning_wallet/dashboard_utama'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Dashboard Utama</span>
                                        </a>
                                    </li>
									<?php endif; ?>

                                </ul>

                            </div>

                        </li>
						<?php endif; ?>
						
                        <!-- Forum Group -->
                        <?php if(has_access('parentmenu.forumgroup',FALSE)): ?>

                        <li class="kt-menu__item <?php echo (($this->uri->segment(1)=='forum_group')OR($this->uri->segment(1)=='forum_group_category')OR($this->uri->segment(1)=='forum_group_topic')OR($this->uri->segment(1)=='forum_group_category_suggest')OR($this->uri->segment(1)=='location')OR($this->uri->segment(1)=='document'))?'kt-menu__item--open':NULL; ?> kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">

                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-icon"><i class="fa fa-project-diagram"></i></span>
                                <span class="kt-menu__link-text">Forum Group</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>

                            <!--Submenu-->
                            <div class="kt-menu__submenu " kt-hidden-height="80" style=""><span class="kt-menu__arrow"></span>

                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">List Forum Group</span>
                                        </span>
                                    </li>

                                    <?php if(has_access('forumgroup.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='forum_group'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('forum_group'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">List Forum Group</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('forumgroupcat.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='forum_group_category'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('forum_group_category?group_id='.$this->session->userdata('group_id')); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Kategori</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('forumgroupcatsuggest.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='forum_group_category_suggest'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('forum_group_category_suggest'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Usulan Kategori</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                </ul>

                            </div>

                        </li>

                        <?php endif; ?>


                        <!-- Digital SOP -->
                        <?php if(has_access('digitalsop.view',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='digital_sop'?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('digital_sop'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-network-wired"></i></span>
                                <span class="kt-menu__link-text">Digital SOP</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <!-- Group -->
                        <?php if(has_access('group.view',FALSE)): ?>
                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='group'?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('group'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-users"></i></span>
                                <span class="kt-menu__link-text">Group</span>
                            </a>
                        </li>
                        <?php endif; ?>


                        <!-- Klien -->
                        <?php if(has_access('klien.view',FALSE)): ?>
                            <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='klien'?'active':NULL; ?>" aria-haspopup="true">
                                <a href="<?php echo site_url('klien'); ?>" class="kt-menu__link ">
                                    <span class="kt-menu__link-icon"><i class="fa fa-boxes"></i></span>
                                    <span class="kt-menu__link-text">Klien</span>
                                </a>
                            </li>
                        <?php endif; ?>


                        <!-- Member -->
                        <?php if(has_access('parentmenu.member',FALSE)): ?>

                        <li class="kt-menu__item <?php echo (($this->uri->segment(1)=='member')OR($this->uri->segment(1)=='member_level')OR($this->uri->segment(1)=='member_bidang')OR($this->uri->segment(1)=='jabatan')OR($this->uri->segment(1)=='location')OR($this->uri->segment(1)=='document'))?'kt-menu__item--open':NULL; ?> kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">

                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-icon"><i class="fa fa-user"></i></span>
                                <span class="kt-menu__link-text">Member</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>

                            <!--Submenu-->
                            <div class="kt-menu__submenu " kt-hidden-height="80" style=""><span class="kt-menu__arrow"></span>

                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">List Member</span>
                                        </span>
                                    </li>

                                    <?php if(has_access('member.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='member'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('member'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">List Member</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('memberlevel.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='member_level'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('member_level'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Level Member</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('memberbidang.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='member_bidang'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('member_bidang'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Bidang Member</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('memberjabatan.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='jabatan'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('jabatan'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Jabatan</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                </ul>

                            </div>

                        </li>

                        <?php endif; ?>

                        <!-- Administrator -->
                        <?php if(has_access('parentmenu.user',FALSE)): ?>

                        <li class="kt-menu__item <?php echo (($this->uri->segment(1)=='user')OR($this->uri->segment(1)=='user_level')OR($this->uri->segment(1)=='user_activity')OR($this->uri->segment(1)=='grade')OR($this->uri->segment(1)=='location')OR($this->uri->segment(1)=='document'))?'kt-menu__item--open':NULL; ?> kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">

                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-icon"><i class="fa fa-user-secret"></i></span>
                                <span class="kt-menu__link-text">Administrator</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>

                            <!--Submenu-->
                            <div class="kt-menu__submenu " kt-hidden-height="80" style=""><span class="kt-menu__arrow"></span>

                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">List Administrator</span>
                                        </span>
                                    </li>

                                    <?php if(has_access('user.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='user'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('user'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">List Administrator</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('userlevel.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='user_level'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('user_level'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Administrator Level</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('useractivity.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='user_activity'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('user_activity'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Log Aktivitas</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                </ul>

                            </div>

                        </li>

                        <?php endif; ?>

                        <!-- Poin -->
                        <?php if(has_access('parentmenu.poin',FALSE)): ?>

                        <li class="kt-menu__item <?php echo (($this->uri->segment(1)=='member_poin')OR($this->uri->segment(1)=='member_saldo') OR($this->uri->segment(1)=='member_poin_dashboard') )?'kt-menu__item--open':NULL; ?> kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">

                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-icon"><i class="fa fa-chevron-circle-up"></i></span>
                                <span class="kt-menu__link-text">Poin</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>

                            <!--Submenu-->
                            <div class="kt-menu__submenu " kt-hidden-height="80" style=""><span class="kt-menu__arrow"></span>

                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">List Poin</span>
                                        </span>
                                    </li>

                                    <?php if(has_access('memberpoinsaldo.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='member_poin_dashboard'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('member_poin_dashboard'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Dashboard Poin & Saldo</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>


                                    <?php if(has_access('memberpoin.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='member_poin'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('member_poin'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Riwayat Poin</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>


                                    <?php if(has_access('membersaldo.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='member_saldo'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('member_saldo'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Riwayat Saldo</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                </ul>

                            </div>

                        </li>

                        <?php endif; ?>

                        <!-- Laporan -->
                        <?php if(has_access('parentmenu.report',FALSE)): ?>

                        <li class="kt-menu__item <?php echo (($this->uri->segment(1)=='report_member')OR($this->uri->segment(1)=='report_ads')OR($this->uri->segment(1)=='report_content_popular')OR($this->uri->segment(1)=='report_content_download')OR($this->uri->segment(1)=='location')OR($this->uri->segment(1)=='document'))?'kt-menu__item--open':NULL; ?> kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">

                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-icon"><i class="fa fa-file"></i></span>
                                <span class="kt-menu__link-text">Laporan</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>

                            <!--Submenu-->
                            <div class="kt-menu__submenu " kt-hidden-height="80" style=""><span class="kt-menu__arrow"></span>

                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Laporan Member</span>
                                        </span>
                                    </li>

                                    <?php if(has_access('reportmember.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='report_member'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('report_member'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Laporan Member</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('reportads.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='report_ads'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('report_ads'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Laporan Iklan</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('reportkontenpopuler.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='report_content_popular'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('report_content_popular'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Konten Popular</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('reportkontendownload.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='report_content_download'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('report_content_download'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Konten Download</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                </ul>

                            </div>

                        </li>

                        <?php endif; ?>

                        <!-- Pengaturan -->
                        <?php if(has_access('parentmenu.config',FALSE)): ?>

                        <li class="kt-menu__item <?php echo (($this->uri->segment(1)=='member_poin_level')OR($this->uri->segment(1)=='member_poin_setting')OR($this->uri->segment(1)=='member_poin_setting_monthly')OR($this->uri->segment(1)=='member_saldo_setting'))?'kt-menu__item--open':NULL; ?> kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">

                            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-icon"><i class="fa fa-cog"></i></span>
                                <span class="kt-menu__link-text">Pengaturan</span>
                                <i class="kt-menu__ver-arrow la la-angle-right"></i>
                            </a>

                            <!--Submenu-->
                            <div class="kt-menu__submenu " kt-hidden-height="80" style=""><span class="kt-menu__arrow"></span>

                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">Pengaturan</span>
                                        </span>
                                    </li>

                                    <?php if(has_access('configlevelpoin.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='member_poin_level'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('member_poin_level'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Config Level Poin</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('configpoin.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='member_poin_setting'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('member_poin_setting'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Config Poin</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <?php if(has_access('configsaldo.view',FALSE)): ?>
                                    <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='member_saldo_setting'?'active':NULL; ?>" aria-haspopup="true">
                                        <a href="<?php echo site_url('member_saldo_setting'); ?>" class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text">Config Saldo</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                </ul>

                            </div>

                        </li>

                        <?php endif; ?>

						<!-- enroll -->
						<?php /*
						<li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='dashboard'?'active':NULL; ?>" aria-haspopup="true">
                            <a target="_blank" href="<?php echo site_url('dashboard/enroll'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa-solid fa-rocket"></i></span>
                                <span class="kt-menu__link-text">Enroll</span>
                            </a>
                        </li>
						*/ ?>

                        <!-- Logout -->
                        <li class="kt-menu__item kt-menu__item--<?php echo $this->uri->segment(1)=='auth/logout'?'active':NULL; ?>" aria-haspopup="true">
                            <a href="<?php echo site_url('auth/logout'); ?>" class="kt-menu__link ">
                                <span class="kt-menu__link-icon"><i class="fa fa-sign-out-alt"></i></span>
                                <span class="kt-menu__link-text">Logout</span>
                            </a>
                        </li>



                    </ul>
                </div>
            </div>
            <!-- end:: Aside Menu -->
        </div>
        <!-- end:: Aside -->

        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

            <!-- begin:: Header -->
            <div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">

                <!-- begin:: Header Menu -->
                <button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
                <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
                    <div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">
                        <ul class="kt-menu__nav ">
                            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
                                <span class="kt-menu__link-text kt-font-lg" style="color:#434349 !important;font-weight: 500 !important"><?php echo isset($page_name)?$page_name:'A Quick Glance'; ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- end:: Header Menu -->

                <!-- begin:: Header Topbar -->
                <div class="kt-header__topbar">

                    <!--begin: User Bar -->
                    <div class="kt-header__topbar-item kt-header__topbar-item--user">
                        <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
                            <div class="kt-header__topbar-user">
                                <span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>
                                <span class="kt-header__topbar-username kt-hidden-mobile"><?= $this->session->userdata('name') ?></span>


                                <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                                <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"><?= substr($this->session->userdata('name'), 0, 1); ?></span>
                            </div>
                        </div>
                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">

                            <!--begin: Head -->
                            <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url(<?php echo base_url('assets'); ?>/media/misc/bg-1.jpg)">
                                <div class="kt-user-card__avatar">

                                    <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                                    <span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success"><?= substr($this->session->userdata('name'), 0, 1); ?></span>
                                </div>
                                <div class="kt-user-card__name">
                                   <?= $this->session->userdata('name') ?><br>
                                    <?= $this->session->userdata('email') ?>
                                </div>
                            </div>

                            <!--end: Head -->

                            <!--begin: Navigation -->
                            <div class="kt-notification">
                                <a href="<?= site_url('profile/change_password') ?>" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-gear kt-font-warning"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title kt-font-bold">
                                            Ganti Password
                                        </div>
                                    </div>
                                </a>

                                <div class="kt-notification__custom kt-space-between">
                                    <a href="<?php echo base_url('auth/logout'); ?>" class="btn btn-label btn-label-brand btn-sm btn-bold">Logout</a>
                                </div>
                            </div>

                            <!--end: Navigation -->
                        </div>
                    </div>
                    <!--end: User Bar -->
                </div>
                <!-- end:: Header Topbar -->
            </div>

            <?php $this->load->view(@$page); ?>


        </div>
    </div>
</div>

<!-- end:: Page -->



<!-- begin::Scrolltop -->
<div id="kt_scrolltop" class="kt-scrolltop">
    <i class="fa fa-arrow-up"></i>
</div>

<!-- end::Scrolltop -->

<!-- begin::Global Config(global config for global JS sciprts) -->
<script>
    var KTAppOptions = {
        "colors": {
            "state": {
                "brand": "#5d78ff",
                "dark": "#282a3c",
                "light": "#ffffff",
                "primary": "#5867dd",
                "success": "#34bfa3",
                "info": "#36a3f7",
                "warning": "#ffb822",
                "danger": "#fd3995"
            },
            "base": {
                "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
            }
        }
    };

</script>

<!-- end::Global Config -->


<!--begin:: Global Optional Vendors -->
<script src="<?php echo base_url('assets'); ?>/vendors/general/jquery-form/dist/jquery.form.min.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-maxlength/src/bootstrap-maxlength.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-switch/dist/js/bootstrap-switch.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/select2/dist/js/select2.full.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/ion-rangeslider/js/ion.rangeSlider.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/typeahead.js/dist/typeahead.bundle.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/handlebars/dist/handlebars.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/inputmask/dist/jquery.inputmask.bundle.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/inputmask/dist/inputmask/inputmask.date.extensions.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/inputmask/dist/inputmask/inputmask.numeric.extensions.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/nouislider/distribute/nouislider.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/owl.carousel/dist/owl.carousel.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/autosize/dist/autosize.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/clipboard/dist/clipboard.min.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/dropzone/dist/dropzone.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/summernote/dist/summernote.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/markdown/lib/markdown.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-markdown/js/bootstrap-markdown.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-notify/bootstrap-notify.min.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/custom/js/vendors/bootstrap-notify.init.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/jquery-validation/dist/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/jquery-validation/dist/additional-methods.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/raphael/raphael.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/morris.js/morris.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/chart.js/dist/Chart.bundle.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/waypoints/lib/jquery.waypoints.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/counterup/jquery.counterup.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/es6-promise-polyfill/promise.min.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/sweetalert2/dist/sweetalert2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/jquery.repeater/src/lib.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/jquery.repeater/src/jquery.input.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/jquery.repeater/src/repeater.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/general/dompurify/dist/purify.js" type="text/javascript"></script>


<script src="<?php echo base_url('assets'); ?>/vendors/custom/js/vendors/bootstrap-timepicker.init.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/custom/vendors/bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/custom/js/vendors/bootstrap-switch.init.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/custom/js/vendors/bootstrap-datepicker.init.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/custom/js/vendors/bootstrap-markdown.init.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/custom/vendors/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/custom/vendors/jquery-idletimer/idle-timer.min.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/custom/js/vendors/sweetalert2.init.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets'); ?>/vendors/custom/js/vendors/jquery-validation.init.js" type="text/javascript"></script>


<!--end:: Global Optional Vendors -->

<!--begin::Global Theme Bundle(used by all pages) -->
<script src="<?php echo base_url('assets'); ?>/js/scripts.bundle.min.js" type="text/javascript"></script>
<!--end::Global Theme Bundle -->

<script>
    // Remote Modal
    $('body').on('click', '[data-toggle="modal"]', function(){
        $($(this).data("target")+' .modal-body').load($(this).data("remote"));
    });
</script>

<script src="<?php echo base_url('assets'); ?>/tinymce/tinymce.min.js" type="text/javascript"></script>
<script>
    tinymce.init({
        selector:'#content',
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
        ],
        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect | responsivefilemanager | link unlink anchor | forecolor backcolor  | print preview code ",
        image_advtab: true ,

        external_filemanager_path:"../assets/filemanager/",
        filemanager_title:"Responsive Filemanager" ,
        filemanager_sort_by:"date",
        filemanager_descending:"1",
        // relative_urls: true,
		relative_urls: false,
        // external_plugins: { "filemanager" : "/filemanager/plugin.min.js"}
    });

    tinymce.init({
        selector:'.content-tinymce',
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
        ],
        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect | responsivefilemanager | link unlink anchor | forecolor backcolor  | print preview code ",
        image_advtab: true ,

        external_filemanager_path:"../assets/filemanager/",
        filemanager_title:"Responsive Filemanager" ,
        filemanager_sort_by:"date",
        filemanager_descending:"1",
        // relative_urls: true,
		relative_urls: false,
        // external_plugins: { "filemanager" : "/filemanager/plugin.min.js"}
    });
</script>



<script src="<?php echo base_url('assets'); ?>/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $('.date-picker').datepicker({
        format: "dd/mm/yyyy",
        language: 'id',
        todayBtn: 'linked',
        todayHighlight:true,
        autoclose:true
    });
</script>





</body>

<!-- end::Body -->
</html>