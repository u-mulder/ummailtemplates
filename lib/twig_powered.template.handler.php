<?php
/**
 * Генерация дополнительного контента
 * писем с помощью шаблонизатора Twig
 *
 * @author u_mulder <m264695502@gmail.com>
 */
namespace Um\MailTemplate;

class TwigPoweredTemplateHandler implements ITemplateHandler
{

    const
        LOOP_REPLACE_BLOCK_NAME = 'LOOP_%s_REPLACE_BLOCK',
        TWIG_USED = 'TWIG_USED';

    //protected
    //    $loader;

    public function getContent($fields, $template_data)
    {
        if ($this->useTwig($template_data)) {
            $matches = array();
            preg_match_all(
                $this->getLoopRegExp(),
                $template_data['MESSAGE'],
                $matches
            );
            if (sizeof($matches) && !empty($matches[0])) {
                foreach ($matches[1] as $k => $match_id) {
                    /*$data_key = $matches[2][$k];
                    $template = trim($matches[3][$k]);

                    if (isset($fields[$data_key]) && '' != $template) {
                        $ar_values = unserialize($fields[$data_key]);
                        if (is_array($ar_values) && sizeof($ar_values)) {
                            $loop_replace_name = self::getLoopReplaceBlockName($data_key);

                            $fields[$loop_replace_name] = self::getRenderedTpl(
                                $template, array('orders' => $ar_values));  // todo! - правильное название ключа

                            $template_data['MESSAGE'] = preg_replace(
                                self::getLoopRegExp($match_id),
                                '#' . $loop_replace_name . '#',
                                $template_data['MESSAGE']
                            );
                        }
                    }*/
                }
            } else {
                // what to do? it means there are no twig data or it can be misunderstood by regexp
            }
        }

        return $result;
    }


    protected function useTwig($template_data)
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


    protected function _getRenderedTpl($template, $data) {
        /*self::initTwig($template);
        $twig = new Twig_Environment(self::$loader);

        return $twig->render('list.html', $data);*/
    }


    protected function initTwig($template)    // TODO
    {
        // Собрать все шаблоны в 1 или каждый раз объявлять новый объект?
        // Подключить автолоадер твига

        $this->$loader = new Twig_Loader_Array(array(
            'list.html' => $template
        ));
    }


    protected function getLoopReplaceBlockName($name)
    {
        $name = trim($name);
        return sprintf(self::LOOP_REPLACE_BLOCK_NAME, $name);
    }

}
