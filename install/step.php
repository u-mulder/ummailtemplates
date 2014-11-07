<?php
if(!check_bitrix_sessid())
    return;
global $errors;
echo CAdminMessage::ShowNote(GetMessage('UMT_MOD_INST_OK'));?>
<form action="<?=$APPLICATION->GetCurPage()?>">
    <input type="hidden" name="lang" value="<?=LANG?>">
    <input type="submit" name="" value="<?=GetMessage('MOD_BACK')?>">
<form>
