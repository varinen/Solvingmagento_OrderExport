<?php
/**
 * Solvingmagento_OrderExport install script
 * 
 * PHP version 5.3
 * 
 * @category  Knm
 * @package   Solvingmagento_OrderExport
 * @author    Oleg Ishenko <oleg.ishenko@solvingmagento.com>
 * @copyright 2013 Oleg Ishenko
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @version   GIT: <0.1.0>
 * @link      http://www.solvingmagento.com/
 *
 */



$installer = $this;

$installer->startSetup();

$installer->run(
    "INSERT INTO `{$this->getTable('core_email_template')}` 
    (`template_code`, `template_text`, `template_type`, `template_subject`)
    VALUES (
        'New Customer and First Order',
        'A first order by a new customer: {{htmlescape var=\$customer.getName()}}, id: {{var=\$customer.getId()}}',
        '2',
        'A first order by a new customer'
    )"
);


$installer->endSetup();