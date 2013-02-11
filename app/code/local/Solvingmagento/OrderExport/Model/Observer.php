<?php
/**
 * Solvingmagento_OrderExport Observer class
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

/** Solvingmagento_OrderExport_Model_Observer
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

class Solvingmagento_OrderExport_Model_Observer
{
    
    /**
     * Exports an order after it is placed
     * 
     * @param Varien_Event_Observer $observer observer object 
     * 
     * @return boolean
     */
    public function exportOrder(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        
        Mage::getModel('solvingmagento_orderexport/export')
            ->exportOrder($order);
        
        return true;
        
    }
    
    /**
     * Sends an email to the admin after a customer places his first order
     * online
     * 
     * @param Varien_Event_Observer $observer observer object
     * 
     * @return boolean
     */
    public function newCustomer(Varien_Event_Observer $observer)
    {
        $orderIds = $observer->getEvent()->getOrderIds();
        
        if (!is_array($orderIds) || (!array_key_exists(0, $orderIds))) {
            return;
        }
        
        $order = Mage::getModel('sales/order')->load($orderIds[0]);
        
        if (!$order->getId()) {
            return;
        }
        
        if (!$order->getCustomerId()) {
            //send a message only for registered customers
            return;
        }
        
        $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
        
        if (!$customer->getId()) {
            return;
        }
        
        $customerOrders = Mage::getModel('sales/order')
                ->getCollection()
                ->addAttributeToFilter('customer_id', $customer->getId());
        if (count($customerOrders) > 1) {
            // send a message only after the first order
            return;
        }
        
        return Mage::getModel('solvingmagento_orderexport/customer')
            ->newCustomer($customer, $order);        
    }
}