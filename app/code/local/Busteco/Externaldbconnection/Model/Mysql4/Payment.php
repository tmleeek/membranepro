<?php
 
class Busteco_Externaldbconnection_Model_Mysql4_Payment extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('externaldbconnection/payment', 'entity_id');
    }
} 