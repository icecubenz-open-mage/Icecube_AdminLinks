<?php

class Icecube_AdminLinks_Block_Adminhtml_Productlink_Button extends Mage_Adminhtml_Block_Widget_Button
{
    /**
     * @var Mage_Catalog_Model_Product Product instance
     */
    private $_product;

    /**
     * Block construct, setting data for button, getting current product
     */
    protected function _construct()
    {
        $this->_product = Mage::registry('current_product');
        $storeId = $this->_getStoreId();
        parent::_construct();

        $destinationUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, true) . $this->_product->setStoreId($storeId)->getUrlPath();

        $this->setData(array(
            'label'     => Mage::helper('catalog')->__('View Product Page'),
            'onclick'   => 'window.open(\''. $destinationUrl .'\')',
            'disabled'  => !$this->_isVisible(),
            'title' => (!$this->_isVisible()) ?
                Mage::helper('catalog')->__('Product is not visible on frontend') :
                Mage::helper('catalog')->__('View Product Page')
        ));
    }

    /**
     * Checking product visibility
     *
     * @return bool
     */
    private function _isVisible()
    {
        $storeId = $this->_getStoreId();
        return $this->_product->setStoreId($storeId)->isVisibleInCatalog() && $this->_product->setStoreId($storeId)->isVisibleInSiteVisibility();
    }

    /**
     * Current StoreID or first in list (not admin)
     *
     * @return integer
     */
    private function _getStoreId()
    {
        $_storeId = $this->_product->getStoreId();
        $_allStoreIds = $this->_product->getStoreIds();

        if (!empty($_allStoreIds)) {
            return $_storeId;
        } elseif (!empty($_allStoreIds)) {
            return $_allStoreIds[0];
        }
    }
}
