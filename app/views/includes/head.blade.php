<!doctype html>
<html lang="en" ng-app="app" ng-controller="AppController">
<head>
  <title>NetworkEd: Directory of Innovation in Education in Wisconsin and Beyond</title>

  <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">

  <link rel="icon" type="image/x-icon" href="/favicon.ico" />
  <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">

  @if (isset($post_info))
  <meta property="og:title" content="NetworkEd: <?= $post_info['title']; ?>" />
  <meta property="og:image" content="https://wcernetwork.org/uploads/<?= $post_info['thumbnail_sm']; ?>" />
  <meta property="og:description" content="<?= substr(preg_replace('/<[^>]+>/', ' ', $post_info['description']), 0, 250); ?>..." />
  @else
  <meta property="og:site_name" content="NetworkEd" />
  <meta property="og:image" content="https://wcernetwork.org/networked.png" />
  <meta property="og:description" content="A directory of innovation in education in Wisconsin and beyond." />
  @endif
  <meta property="fb:app_id" content="1049191555098283" />

	<link rel="stylesheet" href="<?php echo url(); ?>/assets/css/all.min.css" type="text/css">
  <link rel="stylesheet" href="<?php echo url(); ?>/assets/css/font-awesome.min.css" type="text/css">
  <link rel="stylesheet" href="<?php echo url(); ?>/assets/css/bootstrap-datetimepicker.min.css" type="text/css">
  <link rel="stylesheet" href="<?php echo url(); ?>/assets/css/loading-bar.min.css" type="text/css">
  <link href='//fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>

  <meta name="fragment" content="!">

  @include('includes.js_vars')

  <script type="text/javascript" src='https://www.google.com/recaptcha/api.js'></script>
  <script type="text/javascript" src="<?php echo url(); ?>/assets/js/all.min.js?rel=d85dd233d6"></script>
</head>
<body ng-class="{'modal-open': modalOpen || actions.showAboutPages}" @if ($fixed_nav) class="fixed-navbar" @endif resize resize-window-width="windowWidth" resize-window-height="windowHeight" @if ($search_page) style="height: auto;" @endif ng-cloak>
  <div id="wrapper">