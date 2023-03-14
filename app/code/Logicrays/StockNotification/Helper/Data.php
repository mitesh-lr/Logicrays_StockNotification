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
declare(strict_types=1);

namespace Logicrays\StockNotification\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Registry;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * For Decalre all the method for get the Data
 */
class Data extends AbstractHelper
{
    public const MODULE_ENABLE = 'stocknotification/general/enable';
    public const OUTOFSTOCK_NOTIFICATION_CUSTOMERGROUP = 'stocknotification/general/customerlist';
    public const OUTOFSTOCK_NOTIFICATION_TYPE = 'stocknotification/general/notificationtype';
    public const OUTOFSTOCK_NOTIFICATION_SENDER = 'stocknotification/general/sender_email_identity';
    public const OUTOFSTOCK_NOTIFICATION_EMAIL_TEMPLATE = 'stocknotification/general/email_template';
    public const OUTOFSTOCK_NOTIFICATION_TITLE = 'stocknotification/frontlable/notification_title';
    public const OUTOFSTOCK_NOTIFICATION_BUTTON_TITLE = 'stocknotification/frontlable/button_title';
    public const OUTOFSTOCK_NOTIFICATION_SUCSESS = 'stocknotification/frontlable/sucsess_message';

    /**
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * Check module status
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->scopeConfig->getValue(self::MODULE_ENABLE, ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * Get notification title
     *
     * @return string
     */
    public function getNotificationTitle()
    {
        return $this->scopeConfig->getValue(self::OUTOFSTOCK_NOTIFICATION_TITLE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get notification sucsess
     *
     * @return string
     */
    public function getNotificationSucsess()
    {
        return $this->scopeConfig->getValue(self::OUTOFSTOCK_NOTIFICATION_SUCSESS, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get notification button
     *
     * @return string
     */
    public function getNotificationButton()
    {
        return $this->scopeConfig->getValue(self::OUTOFSTOCK_NOTIFICATION_BUTTON_TITLE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get customer group
     *
     * @return string
     */
    public function getCustomerGroup()
    {
        return $this->scopeConfig->getValue(self::OUTOFSTOCK_NOTIFICATION_CUSTOMERGROUP, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get stock notification type
     *
     * @return string
     */
    public function getStockNotificationType()
    {
        return $this->scopeConfig->getValue(self::OUTOFSTOCK_NOTIFICATION_TYPE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get stock notification sender
     *
     * @return string
     */
    public function getStockNotificationSender()
    {
        return $this->scopeConfig->getValue(self::OUTOFSTOCK_NOTIFICATION_SENDER, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get stock notification template
     *
     * @return string
     */
    public function getStockNotificationTemplate()
    {
        return $this->scopeConfig->getValue(self::OUTOFSTOCK_NOTIFICATION_EMAIL_TEMPLATE, ScopeInterface::SCOPE_STORE);
    }
}
