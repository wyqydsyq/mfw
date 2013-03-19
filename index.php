<?php

$GLOBALS['start'] = microtime();
$GLOBALS['true_get'] = $_GET;

// change this to E_NONE when taking me live!
error_reporting(E_ALL);

// include mfw
include('mfw.php');

// run mfw!
$mfw = new mfw(true);
$mfw->start();