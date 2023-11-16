<?php
use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class CGetStatuses extends CBitrixComponent {
    private function _checkModules() {
        if(!Loader::includeModule('iblock'))
            throw new \Exception(Loc::GetMessage("NO_IBLOCK"));
        return true;
    }

    private function _app() {
        global $APPLICATION;
        return $APPLICATION;
    }

    private function _user() {
        global $USER;
        return $USER;
    }

    public function onPrepareComponentParams($arParams) {
        return $arParams;
    }

    public function executeComponent(): void {
        $this->_checkModules();
        $this->getData();
        if (!empty($this->data))
            $this->updateData();
        else
            $this->arResult["DATA"] = false;
        $this->includeComponentTemplate();
    }

    public function getData(): void {
        $this->data = [];
        $this->listNumbers();
        if (!empty($this->arNumbers)) {
            $url = "https://".$this->arParams["DOMAIN"].$this->arParams["PATH"]."?".$this->arParams["VAR"]."=".implode(',', $this->arNumbers)."&json=Y";
            $connect = curl_init($url);
            curl_setopt($connect, CURLOPT_URL, $url);
            curl_setopt($connect, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($connect, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($connect, CURLOPT_TIMEOUT, 5);
            curl_setopt($connect, CURLOPT_CONNECTTIMEOUT, 5);
            $error = curl_error($connect);
            if ($error)
                throw new \Exception($error);
            $json_string = curl_exec($connect);
            curl_close($connect);
            $this->data = json_decode($json_string, true);
        }
    }

    public function listNumbers(): void {
        $this->finalStatuses = [];
        $res = CIBlockElement::GetList([], ["IBLOCK_ID" => $this->arParams["IBLOCK_ID_STATUSES"], "ACTIVE" => "Y", "PROPERTY_FINAL_STATUS_VALUE" => Loc::GetMessage("YES_VALUE")], false, false, ["ID"]);
        while($ob = $res->GetNextElement())
        {
            $arFields = $ob->GetFields();
            $this->finalStatuses[] = $arFields["ID"];
        }
        $this->arNumbers = [];
        $res = CIBlockElement::GetList([], ["IBLOCK_ID" => $this->arParams["IBLOCK_ID_NUMBER"], "ACTIVE" => "Y", "!PROPERTY_STATUS" => $this->finalStatuses], false, false, ["ID", "NAME"]);
        while($ob = $res->GetNextElement())
        {
            $arFields = $ob->GetFields();
            $this->arNumbers[$arFields["ID"]] = $arFields["NAME"];
        }
    }


    public function updateData(): void {
        $this->allStatuses();
        foreach ($this->data as $number => $events) {    
            $number_id = array_search($number, $this->arNumbers); 
            foreach ($events as $key => $event) {
                if (isset($this->statuses[$event["Event"]])) {
                    $status_id = $this->statuses[$event["Event"]];
                }
                else {
                    $el = new CIBlockElement;
                    $status_id = $el->Add([
                        "IBLOCK_SECTION_ID" => false,
                        "IBLOCK_ID" => $this->arParams["IBLOCK_ID_STATUSES"],
                        "NAME" => $event["Event"],
                        "ACTIVE" => "Y"
                    ]);
                    $this->statuses[$event["Event"]] = $status_id;
                }
                $stmp = MakeTimeStamp($event["DateEvent"], "DD.MM.YYYY HH:MI:SS");
                $filter = [
                    "IBLOCK_ID" => $this->arParams["IBLOCK_ID_EVENTS"], 
                    "ACTIVE" => "Y", 
                    "PROPERTY_NUMBER" => $number_id, 
                    "PROPERTY_STATUS" => $status_id, 
                     "PROPERTY_DATE" => date("Y-m-d H:i:s", $stmp)
                ];
                $res = CIBlockElement::GetList([], $filter, false, false, ["ID"]);
                if(!$ob = $res->GetNextElement())
                {
                    $el = new CIBlockElement;
                    if($ID = $el->Add([
                        "IBLOCK_SECTION_ID" => false,
                        "IBLOCK_ID" => $this->arParams["IBLOCK_ID_EVENTS"],
                        "PROPERTY_VALUES" => [
                            "NUMBER" => $number_id,
                            "DATE" => $event["DateEvent"],
                            "STATUS" => $status_id,
                            "DESCRIPTION" => $event["InfoEvent"]
                        ],
                        "NAME" => $number.": ".$event["Event"],
                        "ACTIVE" => "Y"
                    ]))
                        $this->data[$number][$key]["class"] = "table-success";
                    else
                        $this->data[$number][$key]["class"] = "table-warning";
                }
            }
            CIBlockElement::SetPropertyValuesEx($number_id, false, array("STATUS" => $status_id));
        }
        $this->arResult["DATA"] = $this->data;
    }


    public function allStatuses(): void {
        $this->statuses = [];
        $res = CIBlockElement::GetList(["name"=>"asc"], ["IBLOCK_ID" => $this->arParams["IBLOCK_ID_STATUSES"], "ACTIVE" => "Y"], false, false, ["ID", "NAME"]);
        while($ob = $res->GetNextElement())
        {
            $arFields = $ob->GetFields();
            $this->statuses[$arFields["NAME"]] = $arFields["ID"];
        }
    }
}