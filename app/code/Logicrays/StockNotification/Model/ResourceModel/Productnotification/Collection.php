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

namespace Logicrays\StockNotification\Model\ResourceModel\Productnotification;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Define Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Logicrays\StockNotification\Model\Productnotification::class,
            \Logicrays\StockNotification\Model\ResourceModel\Productnotification::class
        );
    }
}
