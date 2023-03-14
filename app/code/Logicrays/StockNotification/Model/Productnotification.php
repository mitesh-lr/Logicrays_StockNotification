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
class Productnotification extends AbstractModel
{
    public const CACHE_TAG = 'logicrays_stocknotification_product_records';

    /**
     * @var string
     */
    protected $_cacheTag = 'logicrays_stocknotification_product_records';

    /**
     * @var string
     */
    protected $_eventPrefix = 'logicrays_stocknotification_product_records';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(\Logicrays\StockNotification\Model\ResourceModel\Productnotification::class);
    }
}
