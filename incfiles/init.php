<?php
ERROR_REPORTING(0);
ob_start();
session_start();
include ($_SERVER['DOCUMENT_ROOT'].'/incfiles/core.php');
include ($_SERVER['DOCUMENT_ROOT'].'/incfiles/settings.php');
include ($_SERVER['DOCUMENT_ROOT'].'/incfiles/connect.php');
include ($_SERVER['DOCUMENT_ROOT'].'/incfiles/function.php');
include ($_SERVER['DOCUMENT_ROOT'].'/incfiles/pagination.php');
include ($_SERVER['DOCUMENT_ROOT'].'/incfiles/urls.php');
include ($_SERVER['DOCUMENT_ROOT'].'/incfiles/bbcode.php');
include ($_SERVER['DOCUMENT_ROOT'].'/incfiles/upload.php');
?>