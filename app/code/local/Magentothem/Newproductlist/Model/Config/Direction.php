<?php
class Magentothem_Newproductlist_Model_Config_Direction
{

    public function toOptionArray()
    {
        return array(
            array('value'=>'asc', 'label'=>Mage::helper('adminhtml')->__('Ascending')),
            array('value'=>'desc', 'label'=>Mage::helper('adminhtml')->__('Descending'))
        );
    }

}
