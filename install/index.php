<?php
IncludeModuleLangFile(__FILE__);

if(class_exists('um_mail_template'))
    return;

class um_mail_template extends CModule {

    var $MODULE_ID = 'um.mail_template',
        $MODULE_VERSION,
        $MODULE_VERSION_DATE,
        $MODULE_NAME,
        $MODULE_DESCRIPTION,
        $PARTNER_NAME,
        $PARTNER_URI,
        $install_path;

	function um_mail_template() {
        $arModuleVersion = array();
        $path = str_replace('\\', '/', __FILE__);
        $path = substr($path, 0, strlen($path) - strlen('/index.php'));
        include($path . '/version.php');
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = GetMessage('UMT_MODULE_NAME');
        $this->MODULE_DESCRIPTION = GetMessage('UMT_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = 'u_mulder';
        $this->PARTNER_URI = '#';

        $this->install_path = $_SERVER['DOCUMENT_ROOT']
            . '/bitrix/modules/' . $this->MODULE_ID . '/install';
    }


    function DoInstall() {
        global $APPLICATION;
        RegisterModule($this->MODULE_ID);

        // TODO
        //RegisterModuleDependences('main', 'OnIBlockPropertyBuildList', $this->MODULE_ID, 'DcMailTemplateIBlockProp', 'getUserTypeDescription');

        // TODO
        \Bitrix\Main\Config\Option::set($this->MODULE_ID,
            'tpl_class_name', 'some_def_name');
        \Bitrix\Main\Config\Option::set($this->MODULE_ID,
            'tpl_class_path', 'no_path');

        $APPLICATION->IncludeAdminFile(GetMessage('UMT_INSTALL_TITLE'),
            $this->install_path . '/step.php');
    }


    function DoUninstall() {
        UnRegisterModule($this->MODULE_ID);
        //UnRegisterModuleDependences('iblock', 'OnIBlockPropertyBuildList', $this->MODULE_ID, 'DcMailTemplateIBlockProp', 'getUserTypeDescription');   // TODO
        // delete options

        global $APPLICATION;
        $APPLICATION->IncludeAdminFile(GetMessage('UMT_UNINSTALL_TITLE'),
            $this->install_path . '/unstep.php');
    }

}
