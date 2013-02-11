<?php
/**
 * Solvingmagento_OrderExport Export class
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

/** Solvingmagento_OrderExport_Model_Export
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
class Solvingmagento_OrderExport_Model_Export
{
    
    /**
     * Generates an XML file from the order data and places it into
     * the var/export directory
     * 
     * @param Mage_Sales_Model_Order $order order object
     * 
     * @return boolean
     */
    public function exportOrder($order) 
    {
        $dirPath = Mage::getBaseDir('var') . DS . 'export';
        
        //if the export directory does not exist, create it
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }
        
        $data = $order->getData();
        
        $xml = new SimpleXMLElement('<root/>');
        array_walk_recursive($data, array ($xml, 'addChild'));
        
        file_put_contents(
            $dirPath. DS .$order->getIncrementId().'.xml', 
            $xml->asXML()
        );
        
        return true;
    }
}