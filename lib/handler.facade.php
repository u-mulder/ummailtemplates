<?php
namespace Um\MailTemplate;

class HandlerFacade
{

    public static function execute(&$fields, &$template_data)
    {
        $tpl_class_path = OptionsHelper::getOption(
            UMT_MODULE_NAME, 'tpl_class_path', '');
        if ('' != $tpl_class_path) {
            $tpl_class_path = realpath($_SERVER['DOCUMENT_ROOT']
                . $tpl_class_path);
            if (file_exists($tpl_class_path)
                && is_file($tpl_class_path)
                && is_readable($tpl_class_path)) {
                $tpl_class_name = OptionsHelper::getOption(
                    UMT_MODULE_NAME, 'tpl_class_name', '');
                if ('' != $tpl_class_name) {
                    require_once $tpl_class_path;
                    if (class_exists($tpl_class_name)) {
                        $obj_tpl = new $tpl_class_name;
                        if ($obj_tpl instanceof ITemplateHandler) {
                            list($fields, $template_data)
                                = $obj_tpl->getContent($fields, $template_data);
                            // Если ошибки выполнения?
                        } else {
                            // класс не поддерживает интерфейс
                        }
                    }
                }
            }
        }

        // TODO - do we need to return something?
    }

}
