<?php
class Magentothem_Producttabs_Block_Random extends Mage_Core_Block_Template
{
	public function getProducts() { 
		$collection = Mage::getResourceModel('catalog/product_collection');
		Mage::getModel('catalog/layer')->prepareProductCollection($collection);
		$collection->getSelect()->order('rand()');
		$collection->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes()) 
			->addMinimalPrice()
			->addUrlRewrite()
			->addTaxPercents()
			->addStoreFilter() 
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
        $collection->setPageSize(Mage::helper('producttabs')->getProductCfg('product_number'));
        return $collection;
    }
}