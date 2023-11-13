<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
if (!Loader::includeModule("iblock"))
{
	return;
}
$iblockExists = (!empty($arCurrentValues["IBLOCK_ID"]) && (int)$arCurrentValues["IBLOCK_ID"] > 0);
$arTypesEx = CIBlockParameters::GetIBlockTypes();
$arIBlocks = [];
$iblockFilter = [
	"ACTIVE" => "Y",
];
if (!empty($arCurrentValues["IBLOCK_TYPE"]))
{
	$iblockFilter["TYPE"] = $arCurrentValues["IBLOCK_TYPE"];
}
if (isset($_REQUEST["site"]))
{
	$iblockFilter["SITE_ID"] = $_REQUEST["site"];
}
$db_iblock = CIBlock::GetList(["SORT"=>"ASC"], $iblockFilter);
while($arRes = $db_iblock->Fetch())
{
	$arIBlocks[$arRes["ID"]] = "[" . $arRes["ID"] . "] " . $arRes["NAME"];
}

$arComponentParameters = [
    "GROUPS" => [
        "PATH" => [
            "NAME" => GetMessage("GET_STATUSES_GROUPS_PATH_NAME"),
            "SORT" => 550
        ],
        "IBLOCK" => [
            "NAME" => GetMessage("GET_STATUSES_GROUPS_IBLOCK_NAME"),
            "SORT" => 550
        ]
    ],
    "PARAMETERS" => [
        "TYPE" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("GET_STATUSES_PARAMETERS_TYPE"),
            "TYPE" => "LIST",
            "MULTIPLE" => "N",
            "DEFAULT" => "json",
            "VALUES" => [
                "json" => "JSON"
            ]
        ],
		"IBLOCK_TYPE_NUMBER" => [
			"PARENT" => "IBLOCK",
			"NAME" => GetMessage("GET_STATUSES_IBLOCK_TYPE_NUMBER"),
			"TYPE" => "LIST",
			"VALUES" => $arTypesEx,
			"DEFAULT" => "",
			"REFRESH" => "Y",
		],
		"IBLOCK_ID_NUMBER" => [
			"PARENT" => "IBLOCK",
			"NAME" => GetMessage("GET_STATUSES_IBLOCK_ID_NUMBER"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"DEFAULT" => "",
			"ADDITIONAL_VALUES" => "Y",
			"REFRESH" => "Y",
		],
		"IBLOCK_ID_EVENTS" => [
			"PARENT" => "IBLOCK",
			"NAME" => GetMessage("GET_STATUSES_IBLOCK_ID_EVENTS"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"DEFAULT" => "",
			"ADDITIONAL_VALUES" => "Y",
			"REFRESH" => "Y",
		],
		"IBLOCK_ID_STATUSES" => [
			"PARENT" => "IBLOCK",
			"NAME" => GetMessage("GET_STATUSES_IBLOCK_ID_STATUSES"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"DEFAULT" => "",
			"ADDITIONAL_VALUES" => "Y",
			"REFRESH" => "Y",
		],
        "DOMAIN" => [
            "PARENT" => "PATH",
            "NAME" => GetMessage("GET_STATUSES_PARAMETERS_DOMAIN"),
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
        ],
        "PATH" => [
            "PARENT" => "PATH",
            "NAME" => GetMessage("GET_STATUSES_PARAMETERS_PATH"),
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
        ],
        "VAR" => [
            "PARENT" => "PATH",
            "NAME" => GetMessage("GET_STATUSES_PARAMETERS_VAR"),
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
        ],
    ],
];
