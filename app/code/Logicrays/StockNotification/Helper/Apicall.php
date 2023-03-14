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

use Magento\Framework\App\Helper\Context;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * For call the api Customer and Admin.
 */
class Apicall extends AbstractHelper
{
    public const SMS_MSG91_API_SENDERID = 'stocknotification/smsgatways/smsmsg91senderid';
    public const SMS_MSG91_API_AUTHKEY = 'stocknotification/smsgatways/smsmsg91authkey';
    public const SMS_MSG91_API_URL = 'stocknotification/smsgatways/smsmsg91apiurl';
    public const OUTOFSTOCK_NOTIFICATION_SMS_CUSTOMER = 'stocknotification/smsgatways/template';
    public const OUTOFSTOCK_NOTIFICATION_SMS_ADMIN = 'stocknotification/smsgatways/admintemplate';
    public const OUTOFSTOCK_NOTIFICATION_SMS_ADMIN_NUMBER = 'stocknotification/smsgatways/mobilenumber';
    public const OUTOFSTOCK_NOTIFICATION_SMS_ADMIN_ENABLE = 'stocknotification/smsgatways/enableadmin';

    /**
     * @var Curl
     */
    protected $curl;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param Context $context
     * @param Curl $curl
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        Curl $curl,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->curl = $curl;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * Get api sender id
     *
     * @return string
     */
    public function getApiSenderId()
    {
        return $this->scopeConfig->
        getValue(self::SMS_MSG91_API_SENDERID, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get authkey
     *
     * @return string
     */
    public function getAuthKey()
    {
        return $this->scopeConfig->
        getValue(self::SMS_MSG91_API_AUTHKEY, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get admin number
     *
     * @return string
     */
    public function getAdminNumber()
    {
        return $this->scopeConfig->
        getValue(self::OUTOFSTOCK_NOTIFICATION_SMS_ADMIN_NUMBER, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get api url
     *
     * @return string
     */
    public function getApiUrl()
    {
        return $this->scopeConfig->
        getValue(self::SMS_MSG91_API_URL, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Validate sms config
     *
     * @return string
     */
    public function validateSmsConfig()
    {
        return $this->getApiUrl() && $this->getAuthKey() && $this->getApiSenderId();
    }

    /**
     * Get out of stock Notification user template
     *
     * @return string
     */
    public function getOutofstockNotificationUserTemplate()
    {
        return $this->scopeConfig->
        getValue(self::OUTOFSTOCK_NOTIFICATION_SMS_CUSTOMER, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Out of stock notification for admin
     *
     * @return boolean
     */
    public function isOutofstockNotificationForAdmin()
    {
        return $this->scopeConfig->
        getValue(self::OUTOFSTOCK_NOTIFICATION_SMS_ADMIN, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get out of stock admin enable
     *
     * @return string
     */
    public function getOutofstockAdminEnable()
    {
        return $this->scopeConfig->
        getValue(self::OUTOFSTOCK_NOTIFICATION_SMS_ADMIN_ENABLE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Call admin api
     *
     * @param int $adminmobile
     * @param string $finalmessage
     * @return void
     */
    public function callApiAdmin($adminmobile, $finalmessage)
    {
        try {
            $url = $this->getApiUrl();
            $authkey = $this->getAuthKey();
            $senderid = $this->getApiSenderId();
            $data = "authkey=$authkey&mobiles=$adminmobile&message=$finalmessage&sender=$senderid&route=4&country=91";

            $this->curl->setOption(CURLOPT_URL, $url);
            $this->curl->setOption(CURLOPT_POST, 1);
            $this->curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
            $this->curl->setOption(CURLOPT_SSL_VERIFYHOST, 2);
            $this->curl->setOption(CURLOPT_POSTFIELDS, $data);
            $this->curl->setOption(CURLOPT_RETURNTRANSFER, 1);

            $this->curl->post($url, $data);
            $responce = $this->curl->getBody();
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Call customer api
     *
     * @param int $mobilenumbers
     * @param string $finalmessage
     * @return void
     */
    public function callApiCustomer($mobilenumbers, $finalmessage)
    {
        try {
            $url = $this->getApiUrl();
            $authkey = $this->getAuthKey();
            $senderid = $this->getApiSenderId();
            $data = "authkey=$authkey&mobiles=$mobilenumbers&message=$finalmessage&sender=$senderid&route=4&country=91";

            $this->curl->setOption(CURLOPT_URL, $url);
            $this->curl->setOption(CURLOPT_POST, 1);
            $this->curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
            $this->curl->setOption(CURLOPT_SSL_VERIFYHOST, 2);
            $this->curl->setOption(CURLOPT_POSTFIELDS, $data);
            $this->curl->setOption(CURLOPT_RETURNTRANSFER, 1);
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
