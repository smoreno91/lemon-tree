<?php

class Magentothem_Configurationswatches_Block_ConfigurableSwatches_Catalog_Media_Js_Product extends Mage_ConfigurableSwatches_Block_Catalog_Media_Js_Product {

    /**
     * Get image fallbacks by product as
     * array(product ID => array( product => product, image_fallback => image fallback ) )
     *
     * @return array
     */
    public function getProductImageFallbacks($keepFrame = null) {
        /* @var $helper Magentothem_Configurationswatches_Helper_ConfigurableSwatches_Mediafallback */
        $helper = Mage::helper('configurableswatches/mediafallback');

        $fallbacks = array();

        $products = $this->getProducts();

        if ($keepFrame === null) {
            $listBlock = $this->getLayout()->getBlock('product_list');
            if ($listBlock && $listBlock->getMode() == 'grid') {
                $keepFrame = true;
            } else {
                $keepFrame = false;
            }
        }

        /* @var $product Mage_Catalog_Model_Product */
        foreach ($products as $product) {
            $imageFallback = $helper->getConfigurableImagesFallbackArray($product, $this->_getImageSizes(), $keepFrame);

            $fallbacks[$product->getId()] = array(
                'product' => $product,
                'image_fallback' => $this->_getJsImageFallbackString($imageFallback)
            );
        }

        return $fallbacks;
    }

}