<?php
/*------------------------------------------------------------------------
 * @version		$Id$
 * @author		Qubesys Technologies Pvt.Ltd (info@qubesys.com)
 * @category	Mc
 * @package		Mc_Eucookie4
 * @copyright	Copyright (C) 2013 - 2014 Qubesys Technologies Pvt.Ltd. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
-------------------------------------------------------------------------*/ 
class Mc_Eucookie4_Model_Config_Style1 
{

    public function toOptionArray()
    {
        return array(
            array('value'=>'1', 'label'=>Mage::helper('adminhtml')->__('Next Tab')),
            array('value'=>'0', 'label'=>Mage::helper('adminhtml')->__('Same Window')),
            
            
        );
    }

}