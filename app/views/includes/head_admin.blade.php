<?php

if ($_SERVER['SERVER_NAME'] == 'the-net.ad.education.wisc.edu')
{
  header('Location: https://wcernetwork.org');
  die();
}

?>

<!doctype html>
<html lang="en" ng-app="adminApp">
<head>
  <title>NetworkEd</title>

  <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">

  <link rel="icon" type="image/x-icon" href="/favicon.ico" />

	<link rel="stylesheet" href="<?php echo url(); ?>/assets/css/all.min.css">
  <link rel="stylesheet" href="<?php echo url(); ?>/assets/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo url(); ?>/assets/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="<?php echo url(); ?>/assets/css/loading-bar.min.css">
  <link href='//fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>

  <meta name="fragment" content="!">

  <script src="//use.typekit.net/qoj1nsk.js"></script>
  <script>try{Typekit.load();}catch(e){}</script>

  @include('includes.js_vars')

  <script type="text/javascript" src="<?php echo url();?>/assets/js/all.min.js"></script>
  <script type="text/javascript" src="<?php echo url();?>/assets/js/admin.min.js"></script>
</head>
<body ng-class="{'modal-open': modalOpen}" @if ($fixed_nav) class="fixed-navbar" @endif resize resize-window-width="windowWidth" resize-window-height="windowHeight" @if ($search_page) style="height: auto;" @endif ng-cloak>
  <div id="wrapper" ng-controller="AdminController">