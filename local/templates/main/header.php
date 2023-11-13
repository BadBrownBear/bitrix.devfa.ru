<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
IncludeTemplateLangFile(__FILE__);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?$APPLICATION->ShowHead();?>
    <link htrf="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <title><?php $APPLICATION->ShowTitle();?></title>
</head>
<body>
<?$APPLICATION->ShowPanel()?>
<div class="container">