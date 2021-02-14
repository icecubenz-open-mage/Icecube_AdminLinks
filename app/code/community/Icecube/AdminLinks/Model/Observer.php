<?php

class Icecube_AdminLinks_Model_Observer
{
    public function addCmsPageButton($observer)
    {
        $_block = $observer->getBlock();

        if ($_block instanceof Mage_Adminhtml_Block_Cms_Page_Edit) {
            $_page = Mage::registry('cms_page');
            $storeId = $this->_getStoreId($_page);

            $destinationUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, true) . $_page->getIdentifier();

            $_block->addButton(
                'icecube_admin_links_cms_button',
                array(
                'label'    => Mage::helper('cms/page')->__('View Page'),
                'onclick'  => 'window.open(\''. $destinationUrl .'\')',
                'disabled' => !$this->_isVisible($_page),
                'title'    => (!$this->_isVisible($_page)) ?
                    Mage::helper('cms')->__('Page is not visible on frontend'):
                    Mage::helper('cms')->__('View Page')
                )
            );
        }

        return $this;
    }

    public function addProductButton($observer)
    {
        $_block = $observer->getBlock();
        $_type  = $_block->getType();
        if ($_type == 'adminhtml/catalog_product_edit') {
            $_block->setChild(
                'product_button',
                $_block->getLayout()->createBlock('icecube_admin_links/adminhtml_product_linkbutton')
            );

            $_deleteButton = $_block->getChild('delete_button');
            /* Prepend the new button to the 'Delete' button if exists */
            if (is_object($_deleteButton)) {
                $_deleteButton->setBeforeHtml($_block->getChild('product_button')->toHtml());
            } else {
                /* Prepend the new button to the 'Reset' button if 'Delete' button does not exist */
                $_resetButton = $_block->getChild('reset_button');
                if (is_object($_resetButton)) {
                    $_resetButton->setBeforeHtml($_block->getChild('product_button')->toHtml());
                }
            }
        }
    }

    /**
    * Checking object visibility
    *
    * @return bool
    */
    private function _isVisible($_object)
    {
        $storeId = $this->_getStoreId($_object);
        return (bool) $_object->setStoreId($storeId)->getIsActive();
    }

    /**
     * Current StoreID or first in list (not admin)
     *
     * @return integer
     */
    private function _getStoreId($_object)
    {
        $_storeId = $_object->getStoreId();
        $_allStoreIds = $_object->getStoreIds();

        if (!empty($_allStoreIds)) {
            return $_storeId;
        } elseif (!empty($_allStoreIds)) {
            return $_allStoreIds[0];
        }
    }
}
