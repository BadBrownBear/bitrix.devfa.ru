<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<div class="row">
    <?if (empty($arResult["DATA"])):?>
        <div class="col">
            <div class="alert alert-danger" role="alert">
            <?=GetMessage("NO_DATA");?>
            </div>
        </div>
    <?else:?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col"><?=GetMessage("TABLE_HEAD_DATE");?></th>
                    <th scope="col"><?=GetMessage("TABLE_HEAD_EVENT");?></th>
                    <th scope="col"><?=GetMessage("TABLE_HEAD_DESCRIPTION");?></th>
                </tr>
            </thead>
            <tbody>
                <?foreach ($arResult["DATA"] as $number => $events):?>
                    <tr>
                        <th colspan="3" scope="row"><?=$number;?></th>
                    </tr>
                    <?foreach ($events as $event):?>
                            <tr class="<?=$event["class"];?>">
                            <td><?=$event["DateEvent"];?></td>
                            <td><?=$event["Event"];?></td>
                            <td><?=$event["InfoEvent"];?></td>
                        </tr>
                    <?endforeach;?>
                <?endforeach;?>
            </tbody>
        </table>
    <?endif;?>
</div>