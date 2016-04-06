<?php
class Magentothem_Newproductlist_Block_Newproductlist extends Mage_Catalog_Block_Product_List
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }

    public function getColumnCount()
    {
        return $this->getConfig('items');
    }

    protected function _getProductCollection()
    { 
		$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
    	$storeId    = Mage::app()->getStore()->getId();
		$collection = Mage::getResourceModel('catalog/product_collection')
			->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
			->addMinimalPrice()
			->addUrlRewrite()
			->addTaxPercents()			
			->addStoreFilter()
			->addAttributeToFilter('news_from_date', array('date'=>true, 'to'=> $todayDate))
			->addAttributeToFilter(array(array('attribute'=>'news_to_date', 'date'=>true, 'from'=>$todayDate), array('attribute'=>'news_to_date', 'is' => new Zend_Db_Expr('null'))),'','left')
			->setOrder($this->getConfig('sort'),$this->getConfig('direction'))
			->addAttributeToSort('news_from_date','desc');		
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
        $limit = (int)$this->getRequest()->getParam('limit') ? (int)$this->getRequest()->getParam('limit') : (int)$this->getToolbarBlock()->getDefaultPerPageValue();
        $collection->setPageSize($limit)->setCurPage($this->getRequest()->getParam('p'));
        Mage::getModel('review/review')->appendSummary($collection);
        //$collection->load();
        return $collection;
    }
	public function getConfig($att) 
	{
		$config = Mage::getStoreConfig('newproductlist');
		if (isset($config['newproductlist_config'][$att]) ) {
			$value = $config['newproductlist_config'][$att];
			return $value;
		}
	}
}