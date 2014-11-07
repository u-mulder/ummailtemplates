<?php
/**
 *
 * @author u_mulder <m264695502@gmail.com>
 */
namespace Um\MailTemplate;

interface ITemplateHandler {

    public function getContent($fields, $template_data);

}
