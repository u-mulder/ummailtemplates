<?php
namespace Um\MailTemplate;

class HandlerFacade
{

    const
        SEVERITY_LEVEL = 'ERROR',
        AUDIT_TYPE = 'UMT_INSTANTIATION';

    protected static
        $instantiation_error = '',
        $template_handler;

    protected static function setTemplateHandler()
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
                        self::$template_handler = new $tpl_class_name;
                    } else {
                        self::$instantiation_error = 'NO_CLASS_EXISTS';
                    }
                } else {
                    self::$instantiation_error = 'NO_CLASS_NAME_OPTION';
                }
            } else {
                self::$instantiation_error = 'NO_OR_BAD_CLASS_FILE';
            }
        } else {
            self::$instantiation_error = 'NO_CLASS_FILE_OPTION';
        }
    }


    public static function execute(&$fields, &$template_data)
    {
        $result = false;

        self::setTemplateHandler();
        if (is_object(self::$template_handler)
            && self::$template_handler instanceof ITemplateHandler) {
            $errors = array();
            $res = self::$template_handler->getContent(
                $fields, $template_data);
            if (empty($errors)) {
                $fields = $res['fields'];
                $template_data = $res['template_data'];
                $result = true;
            } else {
                self::_logError(GetMessage(
                    'TPL_HANDLER_PROC_ERROR',
                    array('#EVENT#' => self::_getEventName($template_data))
                ));
            }
        } else {
            if (!self::$template_handler instanceof ITemplateHandler)
                self::$instantiation_error = 'INTERFACE_NOT_SUPPORTED';

            self::_logError(GetMessage(
                'TPL_HANDLER_INST_ERROR',
                array('#EVENT#' => self::_getEventName($template_data))
            ));
        }

        return $result;
    }


    protected static function _logError($event_name)    // TODO - test this!
    {
        $description = isset(self::$instantiation_error)
            && !empty(self::$instantiation_error)?
            self::$instantiation_error : 'NO_ERROR_DESCRIPTION';

        \CEventLog::Log(
            self::SEVERITY_LEVEL,
            self::AUDIT_TYPE,
            \UMT_MODULE_NAME,
            $event_name,
            GetMessage($description),
            defined('\SITE_ID')? \SITE_ID : false
        );
    }


    protected static function _getEventName($template_data)
    {
        return isset($template_data['EVENT_NAME'])?
            $template_data['EVENT_NAME'] : 'UNKNOWN_MAIL_EVENT';
    }

}
