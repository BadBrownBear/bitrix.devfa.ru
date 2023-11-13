<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class CGetStatuses extends CBitrixComponent {
    public function getJson($x) {
        return 'Получить строку ' . $x;
    }
}