<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

define('MODULE_ID', 'um.mail_template');
CModule::IncludeModule(MODULE_ID);
IncludeModuleLangFile(__FILE__);
$action_path = $APPLICATION->GetCurPage() . '?mid='
    . htmlspecialcharsbx(MODULE_ID) . '&amp;lang=' . LANGUAGE_ID;

$ar_config = array(
    'tpl_class_name' => array('default' => UMT_DEFAULT_CLASS_NAME, 'caption' => GetMessage('UMT_OPT_TPL_CLASS_NAME')),
    'tpl_class_path' => array('default' => UMT_DEFAULT_CLASS_PATH, 'type' => 'select_path', 'caption' => GetMessage('UMT_OPT_TPL_CLASS_PATH'), 'options' => array('form_name' => 'umt_options')),
);

if (isset($_POST['do_save'])) {
    foreach ($ar_config as $code => $data) {
        if (isset($_POST[$code])) {
            $new_val = $_POST[$code];
            $new_val = is_array($new_val)?
                serialize($new_val) : trim($new_val);
            \Um\MailTemplate\OptionsHelper::saveOption(
                MODULE_ID, $code, $new_val);
        }
    }
    LocalRedirect($action_path);
}

$aTabs = array(
    array('DIV' => 'edit1', 'TAB' => GetMessage('UMT_OPT_TITLE'), 'ICON' => '', 'TITLE' => GetMessage('UMT_OPT_CAPTION')),
);
$tabControl = new CAdminTabControl('tabControl', $aTabs);
$tabControl->Begin();?>
<form method="POST" action="<?=$action_path?>" id="FORMACTION" name="umt_options">
<?php
echo bitrix_sessid_post();
$tabControl->BeginNextTab();
foreach ($ar_config as $name => $data) {?>
    <tr>
        <td width="40%"><label for="filter_<?=$name?>"><?=$data['caption']?>:</label></td>
        <td width="60%">
            <?=\Um\MailTemplate\OptionsHelper::getOptionField($name, $data)?>
        </td>
    </tr>
<?php
}
$tabControl->Buttons();?>
    <input type="submit" class="adm-btn-green" name="do_save" value="<?=GetMessage('UMT_SAVE_BTN')?>" />
<?php
$tabControl->End();?>
</form>
