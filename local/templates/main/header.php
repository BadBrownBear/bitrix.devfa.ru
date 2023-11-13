<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
IncludeTemplateLangFile(__FILE__);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?$APPLICATION->ShowHead();?>
    <title><?php $APPLICATION->ShowTitle();?></title>
</head>
<body>
<?$APPLICATION->ShowPanel()?>