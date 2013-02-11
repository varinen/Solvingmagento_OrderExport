<?php
/**
 * Solvingmagento_OrderExport Customer class
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

/** Solvingmagento_OrderExport_Model_Customer
 * 
 * @category Knm
 * @package  Solvingmagento_OrderExport
 * 
 * @author   Oleg Ishenko <oleg.ishenko@solvingmagento.com>
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @version  Release: <package_version>
 * @link     http://www.solvingmagento.com/
 * 
 * 
 */

class Solvingmagento_OrderExport_Model_Customer 
{
    /**
     * Sends a message to the shop admin about a new customer registration
     * 
     * @param Mage_Customer_Model_Customer $customer customer object
     * @param Mage_Sales_Model_Order       $order    order object
     * 
     * @return boolean
     */
    public function newCustomer($customer, $order)
    {
        try {
            $storeId = $order->getStoreId();

            $templateId = Mage::getStoreConfig(
                'sales_email/order/new_customer_template', 
                $storeId
            );

            $mailer = Mage::getModel('core/email_template_mailer');
            $emailInfo = Mage::getModel('core/email_info');
            $emailInfo->addTo(
                Mage::getStoreConfig(Mage_Sales_Model_Order::XML_PATH_EMAIL_IDENTITY)
            );

            $mailer->addEmailInfo($emailInfo);

            // Set all required params and send emails
            $mailer->setSender(
                Mage::getStoreConfig(
                    Mage_Sales_Model_Order::XML_PATH_EMAIL_IDENTITY, 
                    $storeId
                )
            );
            $mailer->setStoreId($storeId);
            $mailer->setTemplateId($templateId);
            $mailer->setTemplateParams(
                array(
                    'customer'  => $customer
                )
            );
            $mailer->send();
        } catch (Exception $e) {
            Mage::logException($e);
            return false;
        } 
        
        return true;

    }
}