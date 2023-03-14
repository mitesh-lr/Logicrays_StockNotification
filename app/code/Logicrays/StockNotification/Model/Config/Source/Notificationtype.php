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

namespace Logicrays\StockNotification\Model\Config\Source;

/**
 * Render notification type at admin side.
 */
class Notificationtype
{
    /**
     * To option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'sms', 'label' => __('SMS')],
            ['value' => 'email', 'label' => __('Email')],
            ['value' => 'smsemail', 'label' => __('SMS and Email Both')]
        ];
    }
}
