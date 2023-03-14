<?php
/**
 * Logicrays
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Logicrays
 * @package     Logicrays_StockNotification
 * @copyright   Copyright (c) Logicrays (https://www.logicrays.com/)
 */
namespace Logicrays\StockNotification\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;
use Logicrays\StockNotification\Helper\Data;
use Magento\Customer\Model\SessionFactory;
use Logicrays\StockNotification\Model\StockNotification;

/**
 * allow passing data and additional functionality to the template file
 */
class OutOfStock implements ArgumentInterface
{
    /**
     * @var StoreManagerInterface
     */
    protected $storemanagerinterface;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @var SessionFactory
     */
    protected $customerSession;

    /**
     * @var StockNotification
     */
    protected $stockNotification;

    /**
     * @param StoreManagerInterface $storemanagerinterface
     * @param Data $helperData
     * @param SessionFactory $sessionFactory
     * @param StockNotification $stockNotification
     */
    public function __construct(
        StoreManagerInterface $storemanagerinterface,
        Data $helperData,
        SessionFactory $sessionFactory,
        StockNotification $stockNotification
    ) {
        $this->storemanagerinterface = $storemanagerinterface;
        $this->helperData = $helperData;
        $this->sessionFactory =  $sessionFactory;
        $this->stockNotification = $stockNotification;
    }

    /**
     * Get helper data
     *
     * @return array
     */
    public function getHelperData()
    {
        return $this->helperData;
    }

    /**
     * Get customer data
     *
     * @return array
     */
    public function getCustomerData()
    {
        $sessionData = $this->sessionFactory->create();
        if ($sessionData) {
            return $sessionData;
        } else {
            return false;
        }
    }

    /**
     * Get stock notification row data
     *
     * @return array
     */
    public function getStocknotificationRowData()
    {
        return $this->stockNotification->getCollection();
    }
}
