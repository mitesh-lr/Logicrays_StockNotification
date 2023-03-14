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

namespace Logicrays\StockNotification\Api\Data;

use Logicrays\StockNotification\Api\Data\StockNotificationInterface;

/**
 * Interface for getter and setter.
 */
interface StockNotificationInterface
{
    public const ENTITY_ID = 'entity_id';
    public const WEBSITE = 'websitename';
    public const PRODUCTID = 'product_id';
    public const PRODUCTNAME = 'product_name';
    public const PRODUCTSKU = 'product_sku';
    public const SUBSCRIBERNAME = 'subscriber_name';
    public const SUBSCRIBEREMAIL = 'subscriber_email';
    public const SUBSCRIBERMOBILE = 'subscriber_mobile';
    public const CREATED_AT = 'created_at';
 
    /**
     * Get entityid.
     *
     * @return int
     */
    public function getEntityId();

    /**
     * Set entityid
     *
     * @param int $entityId
     * @return StockNotificationInterface
     */
    public function setEntityId($entityId);

    /**
     * Get websitename.
     *
     * @return int
     */
    public function getWebsitename();

    /**
     * Set websitename
     *
     * @param string $websitename
     * @return StockNotificationInterface
     */
    public function setWebsitename($websitename);

    /**
     * Get product_id.
     *
     * @return int
     */
    public function getProductId();

    /**
     * Set productid
     *
     * @param int $product_id
     * @return StockNotificationInterface
     */
    public function setProductId($product_id);

    /**
     * Get product_name.
     *
     * @return string
     */
    public function getProductName();

    /**
     * Set product_name
     *
     * @param string $product_name
     * @return StockNotificationInterface
     */
    public function setProductName($product_name);

    /**
     * Get product_sku.
     *
     * @return string
     */
    public function getProductSku();

    /**
     * Set product_sku
     *
     * @param string $product_sku
     * @return StockNotificationInterface
     */
    public function setProductSku($product_sku);

    /**
     * Get subscriber_name.
     *
     * @return string
     */
    public function getSubscriberName();

    /**
     * Set subscriber_name
     *
     * @param string $subscriber_name
     * @return StockNotificationInterface
     */
    public function setSubscriberName($subscriber_name);

    /**
     * Get subscriber_email.
     *
     * @return string
     */
    public function getSubscriberEmail();

    /**
     * Set subscriber_email
     *
     * @param string $subscriber_email
     * @return StockNotificationInterface
     */
    public function setSubscriberEmail($subscriber_email);

    /**
     * Get subscriber_mobile.
     *
     * @return int
     */
    public function getSubscriberMobile();

    /**
     * Set subscriber_mobile
     *
     * @param int $subscriber_mobile
     * @return StockNotificationInterface
     */
    public function setSubscriberMobile($subscriber_mobile);

    /**
     * Get createdAt.
     *
     * @return int
     */
    public function getCreatedAt();

    /**
     * Set subscriber_mobile
     *
     * @param int $createdAt
     * @return StockNotificationInterface
     */
    public function setCreatedAt($createdAt);
}
