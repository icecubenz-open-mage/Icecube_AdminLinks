<?php

class Icecube_AdminPreviewLinks_Block_Adminhtml_Catalog_Category_Edit_Buttons extends Mage_Adminhtml_Block_Catalog_Category_Abstract
{
    /**
     * @var Mage_Catalog_Model_Category Category instance
     */
    private $_category;

    public function addPreviewButton()
    {
        $this->_category = $this->getCategory();

        $storeId = $this->_getStoreId();
        $destinationUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, true) . $this->_category->getUrlPath();

        // only show button for active categories and not add new category page
        if ($this->_isActive()) {
            $this->getParentBlock()->getChild('form')
                ->addAdditionalButton('icecube_admin_preview_links_category_button', array(
                    'label'    => Mage::helper('catalog')->__('View Category Page'),
                    'onclick'  => 'window.open(\''. $destinationUrl .'\')',
                    'disabled' => !$this->_isActive(),
                    'title'    => (!$this->_isActive()) ?
                        Mage::helper('catalog')->__('Category is not visible on frontend') :
                        Mage::helper('catalog')->__('View Category Page')
            ));
        }
        return $this;
    }

    /**
     *
     * Checking category visibility
     *
     * @return bool
     */
    private function _isActive()
    {
        $storeId = $this->_getStoreId();
        return $this->_category->setStoreId($storeId)->getIsActive() && $this->_category->setStoreId($storeId)->getIsActive();
    }

    /**
     * Current StoreID or first in list (not admin)
     *
     * @return integer
     */
    private function _getStoreId()
    {
        $_storeId = $this->_category->getStoreId();
        $_allStoreIds = $this->_category->getStoreIds();

        if (!empty($_allStoreIds)) {
            return $_storeId;
        } elseif (!empty($_allStoreIds)) {
            return $_allStoreIds[0];
        }
    }
}
