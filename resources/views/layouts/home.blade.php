<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!--
        ===
        This comment should NOT be removed.

        Charisma v2.0.0

        Copyright 2012-2014 Muhammad Usman
        Licensed under the Apache License v2.0
        http://www.apache.org/licenses/LICENSE-2.0

        http://usman.it
        http://twitter.com/halalit_usman
        ===
    -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', '충남농작업지원단 업무시스템') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Charisma, a fully featured, responsive, HTML5, Bootstrap admin template.">
    <meta name="author" content="Muhammad Usman">

    <!-- The styles -->
    <link id="bs-css" href="/css/bootstrap-cerulean.min.css" rel="stylesheet">

    <link href="/css/charisma-app.css" rel="stylesheet">
    <link href='/bower_components/fullcalendar/dist/fullcalendar.css' rel='stylesheet'>
    <link href='/bower_components/fullcalendar/dist/fullcalendar.print.css' rel='stylesheet' media='print'>
    <link href='/bower_components/chosen/chosen.min.css' rel='stylesheet'>
    <link href='/bower_components/colorbox/example3/colorbox.css' rel='stylesheet'>
    <link href='/bower_components/responsive-tables/responsive-tables.css' rel='stylesheet'>
    <link href='/bower_components/bootstrap-tour/build/css/bootstrap-tour.min.css' rel='stylesheet'>
    <link href='/css/jquery.noty.css' rel='stylesheet'>
    <link href='/css/noty_theme_default.css' rel='stylesheet'>
    <link href='/css/elfinder.min.css' rel='stylesheet'>
    <link href='/css/elfinder.theme.css' rel='stylesheet'>
    <link href='/css/jquery.iphone.toggle.css' rel='stylesheet'>
    <link href='/css/uploadify.css' rel='stylesheet'>
    <link href='/css/animate.min.css' rel='stylesheet'>
    @yield('style')

    <!-- jQuery -->
    <script src="/bower_components/jquery/jquery.min.js"></script>

    <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- The fav icon -->
    <link rel="shortcut icon" href="/img/favicon.ico">
    <style>
    body {-ms-overflow-style: none;
    	background-image:max-height:100%; min-height:1080px;
    }
    ::-webkit-scrollbar { display: none; }
    </style>

</head>
<body style="background-image:url('/img/login-background.png'); background-repeat:no-repeat; background-size:1920px 100%; overflow-y:scroll;">
  @include('layouts.partial.top-nav')

  <div class="ch-container" style="padding-left:0;">
    <!-- <div style="background-color:#323333; width:210px; height:100%; position:absolute;"></div> -->
    <div style="background-color:#323333; width:210px; height:100%; position:fixed;"></div>
    <div class="row">
      <div class="col-sm-2 col-lg-2">
        @include('layouts.partial.left-nav')
      </div>
      <div id="content" class="col-lg-10 col-sm-10">
        @include('flash::message')
        @yield('content')
      </div>
    </div>
  </div>
</body>

<!-- external javascript -->

<script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- library for cookie management -->
<script src="/js/jquery.cookie.js"></script>
<!-- calender plugin -->
<script src='/bower_components/moment/min/moment.min.js'></script>
<script src='/bower_components/fullcalendar/dist/fullcalendar.min.js'></script>
<!-- data table plugin -->
<script src='/js/jquery.dataTables.min.js'></script>

<!-- select or dropdown enhancer -->
<script src="/bower_components/chosen/chosen.jquery.min.js"></script>
<!-- plugin for gallery image view -->
<script src="/bower_components/colorbox/jquery.colorbox-min.js"></script>
<!-- notification plugin -->
<script src="/js/jquery.noty.js"></script>
<!-- library for making tables responsive -->
<script src="/bower_components/responsive-tables/responsive-tables.js"></script>
<!-- tour plugin -->
<script src="/bower_components/bootstrap-tour/build/js/bootstrap-tour.min.js"></script>
<!-- star rating plugin -->
<script src="/js/jquery.raty.min.js"></script>
<!-- for iOS style toggle switch -->
<script src="/js/jquery.iphone.toggle.js"></script>
<!-- autogrowing textarea plugin -->
<script src="/js/jquery.autogrow-textarea.js"></script>
<!-- multiple file upload plugin -->
<script src="/js/jquery.uploadify-3.1.min.js"></script>
<!-- history.js for cross-browser state change on ajax -->
<script src="/js/jquery.history.js"></script>
<!-- application script for Charisma demo -->
<script src="/js/charisma.js"></script>
@yield('script')

</html>
