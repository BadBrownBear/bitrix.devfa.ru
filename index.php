<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Тестовый сайт");
?><?$APPLICATION->IncludeComponent(
	"dev:getStatuses", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"TYPE" => "json",
		"DOMAIN" => "newpartner.ru",
		"PATH" => "/app/tracking.php",
		"VAR" => "f001",
		"IBLOCK_TYPE_NUMBER" => "delivery",
		"IBLOCK_ID_NUMBER" => "1",
		"IBLOCK_ID_EVENTS" => "2",
		"IBLOCK_ID_STATUSES" => "3",
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>