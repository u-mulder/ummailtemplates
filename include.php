<?php
define('UMT_MODULE_NAME', 'um.mail_template');
define('UMT_DEFAULT_CLASS_NAME', '\Um\MailTemplate\TwigPoweredTemplateHandler');
define(
    'UMT_DEFAULT_CLASS_PATH',
    '/bitrix/modules/' . UMT_MODULE_NAME
        . '/lib/twig_powered.template.handler.php'
);

$autoloaded_classes = array(
    'Um\MailTemplate\OptionsHelper' => 'lib/options.helper.php',
    '\Um\MailTemplate\OptionsHelper' => 'lib/options.helper.php',
    'Um\MailTemplate\HandlerFacade' => 'lib/handler.facade.php',
    '\Um\MailTemplate\HandlerFacade' => 'lib/handler.facade.php',
    'Um\MailTemplate\ITemplateHandler' => 'lib/itemplate.handler.php',
    '\Um\MailTemplate\ITemplateHandler' => 'lib/itemplate.handler.php',
);

if (class_exists('\Bitrix\Main\Loader'))
    \Bitrix\Main\Loader::registerAutoLoadClasses(UMT_MODULE_NAME, $autoloaded_classes);
else
    \CModule::AddAutoloadClasses(UMT_MODULE_NAME, $autoloaded_classes);
