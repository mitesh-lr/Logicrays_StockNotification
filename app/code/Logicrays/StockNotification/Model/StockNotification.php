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

namespace Logicrays\StockNotification\Model;

use Logicrays\StockNotification\Api\Data\StockNotificationInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Defiine Model
 */
class StockNotification extends AbstractModel implements StockNotificationInterface
{
    public const CACHE_TAG = 'logicrays_stocknotification_records';

    /**
     * @var string
     */
    protected $_cacheTag = 'logicrays_stocknotification_records';

    /**
     * @var string
     */
    protected $_eventPrefix = 'logicrays_stocknotification_records';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(\Logicrays\StockNotification\Model\ResourceModel\StockNotification::class);
    }

    /**
     * Get entity id
     *
     * @return int
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }
    
    /**
     * Set entity id
     *
     * @param int $entityId
     * @return int
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Get website name
     *
     * @return string
     */
    public function getWebsitename()
    {
        return $this->getData(self::WEBSITE);
    }

    /**
     * Set website name
     *
     * @param string $websitename
     * @return string
     */
    public function setWebsitename($websitename)
    {
        return $this->setData(self::WEBSITE, $websitename);
    }

    /**
     * Get product_id
     *
     * @return int
     */
    public function getProductId()
    {
        return $this->getData(self::PRODUCTID);
    }

    /**
     * Set product_id
     *
     * @param int $product_id
     * @return int
     */
    public function setProductId($product_id)
    {
        return $this->setData(self::PRODUCTID, $product_id);
    }

    /**
     * Get product_name
     *
     * @return string
     */
    public function getProductName()
    {
        return $this->getData(self::PRODUCTNAME);
    }

    /**
     * Set product_name
     *
     * @param string $product_name
     * @return string
     */
    public function setProductName($product_name)
    {
        return $this->setData(self::PRODUCTNAME, $product_name);
    }

    /**
     * Get product_sku
     *
     * @return string
     */
    public function getProductSku()
    {
        return $this->getData(self::PRODUCTSKU);
    }

    /**
     * Set product_sku
     *
     * @param string $product_sku
     * @return string
     */
    public function setProductSku($product_sku)
    {
        return $this->setData(self::PRODUCTSKU, $product_sku);
    }

    /**
     * Get subscriber_name
     *
     * @return string
     */
    public function getSubscriberName()
    {
        return $this->getData(self::SUBSCRIBERNAME);
    }

    /**
     * Set subscriber_name
     *
     * @param string $subscriber_name
     * @return string
     */
    public function setSubscriberName($subscriber_name)
    {
        return $this->setData(self::SUBSCRIBERNAME, $subscriber_name);
    }

    /**
     * Get subscriber_email
     *
     * @return string
     */
    public function getSubscriberEmail()
    {
        return $this->getData(self::SUBSCRIBEREMAIL);
    }

    /**
     * Set subscriber_email
     *
     * @param string $subscriber_email
     * @return string
     */
    public function setSubscriberEmail($subscriber_email)
    {
        return $this->setData(self::SUBSCRIBEREMAIL, $subscriber_email);
    }

    /**
     * Get subscriber_mobile
     *
     * @return int
     */
    public function getSubscriberMobile()
    {
        return $this->getData(self::SUBSCRIBERMOBILE);
    }

    /**
     * Set subscriber_mobile
     *
     * @param int $subscriber_mobile
     * @return int
     */
    public function setSubscriberMobile($subscriber_mobile)
    {
        return $this->setData(self::SUBSCRIBERMOBILE, $subscriber_mobile);
    }

    /**
     * Get createdAt
     *
     * @return date
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set createdAt
     *
     * @param date $createdAt
     * @return date
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
}
