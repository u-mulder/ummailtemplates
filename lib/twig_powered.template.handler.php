<?php
/**
 * Генерация дополнительного контента
 * писем с помощью шаблонизатора Twig
 *
 * // TODO - add some error checking!
 *
 * @author u_mulder <m264695502@gmail.com>
 */
namespace Um\MailTemplate;

class TwigPoweredTemplateHandler implements ITemplateHandler
{

    const
        LOOP_REPLACE_BLOCK_NAME = 'LOOP_%s_REPLACE_BLOCK',
        TEMPLATE_EXTENSION = '.html',
        TWIG_USED = 'TWIG_USED';

    protected
        $twig;

    public function getContent($fields, $template_data)
    {
        $result = array(
            'fields' => $fields,
            'template_data' => $template_data,
            'errors' => array()
        );

        if ($this->templatingAllowed($template_data)) {
            $matches = array();
            preg_match_all(
                $this->getLoopRegExp(),
                $template_data['MESSAGE'],
                $matches
            );

            if (sizeof($matches) && !empty($matches[0])) {
                $templates = $arguments = array();
                foreach ($matches[1] as $k => $match_id) {
                    $data_key = $matches[2][$k];
                    $template = trim($matches[3][$k]);

                    if (isset($fields[$data_key])
                        && $fields[$data_key]
                        && '' != $template) {
                        $var_name = strtolower($data_key);
                        $cur_arguments = unserialize($fields[$data_key]);
                        if (is_array($cur_arguments)) {
                            $arguments[$var_name] = $cur_arguments;
                            $templates[] = array(
                                'tpl_name' => $var_name,
                                'tpl_content' => $template,
                                'loop_replace_name' =>
                                    $this->getLoopReplaceBlockName($data_key),
                                'loop_replace_regexp' =>
                                    $this->getLoopRegExp($match_id)
                            );
                        }
                    }
                }
                if (!empty($templates)) {
                    $this->initTemplateEngine($templates);
                    foreach ($templates as $tpl)
                        $result['template_data']['MESSAGE'] = preg_replace(
                            $tpl['loop_replace_regexp'],
                            $this->getRenderedBlock($tpl['tpl_name'],
                                $arguments[$tpl['tpl_name']]),
                            $result['template_data']['MESSAGE']
                        );
                }
            } else {
                // what to do? it means there are no twig data or it can be misunderstood by regexp
            }
        }

        return $result;
    }


    protected function templatingAllowed($template_data)
    {
        return (isset($template_data['FIELD1_NAME'])
            && $template_data['FIELD1_NAME'] == self::TWIG_USED
            && (bool)$template_data['FIELD1_VALUE'])
            || (isset($template_data['FIELD2_NAME'])
            && $template_data['FIELD2_NAME'] == self::TWIG_USED
            && (bool)$template_data['FIELD2_VALUE']);
    }


    protected function getLoopRegExp($idx = -1)
    {
        $idx = intval($idx);

        return '/\#FOR_LOOP_START_' . ($idx < 1? '(\d+)' : $idx)
            . '\#\#LOOP_SOURCE\:([A-Za-z0-9_-]+)\#(.*)'
            . '\#FOR_LOOP_END_' . ($idx < 1? '(\1)' : $idx) . '\#/is';
    }


    protected function getRenderedBlock($tpl_name, $data)
    {
        return $this->twig->render(
            $tpl_name . self::TEMPLATE_EXTENSION, array($tpl_name => $data));
    }


    protected function initTemplateEngine($templates)
    {
        // assuming twig is always here // TODO
        require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/'
            . UMT_MODULE_NAME . '/lib/template_engines/Twig/Autoloader.php';
        \Twig_Autoloader::register();

        $loader = new \Twig_Loader_Array(array_reduce(
            $templates,
            function ($t, $v) {
                $t[$v['tpl_name'] . self::TEMPLATE_EXTENSION] = $v['tpl_content'];

                return $t;
            },
            array()
        ));
        $this->twig = new \Twig_Environment($loader);
    }


    protected function getLoopReplaceBlockName($name)
    {
        $name = trim($name);
        return sprintf(self::LOOP_REPLACE_BLOCK_NAME, $name);
    }

}
