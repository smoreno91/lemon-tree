<?php

class Magentothem_Configurationswatches_Block_ConfigurableSwatches_Catalog_Media_Js_List
    extends Mage_ConfigurableSwatches_Block_Catalog_Media_Js_List {

    private $_swatchesProductCollection = array();

    public function setSwatchesProductCollection($collection) {
        $this->_swatchesProductCollection = $collection;
    }

    public function getSwatchesProductCollection() {
        return $this->_swatchesProductCollection;
    }

    /**
     * Set swatches product collection by category ID
     *
     * @param $category
     */
    public function getAllProductsByCategory($category) {
        $cateIds = array();
        $productIds = array();
        $productCollection = array();
        $storeId = Mage::app()->getStore()->getStoreId();
        array_push($cateIds, $category->getId());
        $subCateIds = explode(',', $category->getChildren());
        foreach ($subCateIds as $subCateId) {
            array_push($cateIds, $subCateId);
        }

        foreach ($cateIds as $cateId) {
            $products = Mage::getModel('catalog/category')->load($cateId)
                ->getProductCollection()
                ->addStoreFilter($storeId)
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('status', 1)
                ->addAttributeToFilter('visibility', 4);
            foreach ($products as $product) {
                array_push($productIds, $product->getId());
            }
        }

        $productIds = array_unique($productIds);
        foreach ($productIds as $productId) {
            $product = Mage::getModel('catalog/product')->load($productId);
            array_push($productCollection, $product);
        }

        $this->setSwatchesProductCollection($productCollection);
    }

    /**
     * Set swatches product collection by search text
     *
     * @param $text_param
     */
    public function getAllProductsBySearchText($text_param) {
        $productIds = array();
        $productCollection = array();
        $storeId = Mage::app()->getStore()->getStoreId();
        $products = Mage::getResourceModel('catalog/product_collection')
            ->addStoreFilter($storeId)
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status', 1)
            ->addAttributeToFilter('visibility', 4)
            ->addAttributeToFilter('name', array('like' => '%'.$text_param.'%'))
            ->load();

        foreach ($products as $product) {
            array_push($productIds, $product->getId());
        }

        foreach ($productIds as $productId) {
            $product = Mage::getModel('catalog/product')->load($productId);
            array_push($productCollection, $product);
        }

        $this->setSwatchesProductCollection($productCollection);
    }

    /**
     * Get target product IDs from product collection
     * which was set on block
     *
     * @return array
     */
    public function getProducts() {
        /* Detect category */
        $current_category = Mage::registry('current_category');
        if($current_category) {
            $this->getAllProductsByCategory($current_category);
        }

        /* Detect search */
        if(Mage::app()->getRequest()->getModuleName() == 'catalogsearch') {
            $text_param = $this->getRequest()->getParam('q');
            $this->getAllProductsBySearchText($text_param);
        }

        return $this->getSwatchesProductCollection();
    }
}