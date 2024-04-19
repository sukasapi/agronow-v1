
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
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-page--loading">

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
        external_plugins: { "filemanager" : "/filemanager/plugin.min.js"}
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
        external_plugins: { "filemanager" : "/filemanager/plugin.min.js"}
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