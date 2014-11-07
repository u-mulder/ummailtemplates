<?php
namespace Um\MailTemplate;

class OptionsHelper
{

    public static function getOptionField($name, array $data)
    {
        $result = '';
        $name = htmlspecialchars(trim($name));

        if ('' != $name && sizeof($data)) {
            $cur_val = self::getOption(UMT_MODULE_NAME, $name, $data['default']);

            switch ($data['type']) {
                // do it yourself if needed
                case 'multiplelist':
                case 'singlelist':
                    break;

                case 'select_path':
                    $result = self::drawSelectPath($name, $cur_val,
                        $data['options']['form_name']);
                    break;

                default:
                    $result = self::drawString($name, $cur_val);
                    break;
            }
        }

        return $result;
    }


    public static function drawString($name, $value)
    {
        return '<input type="text" name="' . $name . '" value="'
            . htmlspecialchars($value) . '" />';
    }


    public static function drawSelectPath($name, $value, $form_name)
    {
        $event_name = 'btnClick_' . uniqid();
        \CAdminFileDialog::ShowScript(array(
            'event' => $event_name,
            'arResultDest' => array('FORM_NAME' => $form_name, 'FORM_ELEMENT_NAME' => $name),
            'arPath' => array('PATH' => GetDirPath($value)),
            'select' => 'F',
            'operation' => 'O',
            'showUploadTab' => false,
            'showAddToMenuTab' => false,
            'fileFilter' => 'php',
            'allowAllFiles' => true,
            'SaveConfig' => true,
        ));

        return '<input type="text" name="' . $name
            . '" size="50"  maxlength="255" value="'
            . htmlspecialchars($value) . '">&nbsp;<input type="button"'
            . ' name="browse" value="..." onClick="' . $event_name . '()">';
    }


    public static function getOption($module_name, $name, $default_value)
    {
        if (class_exists('\Bitrix\Main\Config\Option'))
            $result = \Bitrix\Main\Config\Option::get(
                $module_name, $name, $default_value);
        else
            $result = \COption::getOptionString(
                $module_name, $name, $default_value);

        return $result;
    }


    public static function saveOption($module_name, $name, $value)
    {
        \COption::SetOptionString($module_name, $name, $value);
    }

}
