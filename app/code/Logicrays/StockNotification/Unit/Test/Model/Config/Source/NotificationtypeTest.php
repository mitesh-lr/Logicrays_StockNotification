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

namespace Logicrays\StockNotification\Unit\Test\Model\Config\Source;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Logicrays\StockNotification\Model\Config\Source\Notificationtype;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for render message type at admin side.
 */
class NotificationtypeTest extends TestCase
{
    /** @var Notificationtype */
    protected $object;

    /** @var ObjectManager */
    protected $objectManager;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);
        $this->object = $this->objectManager->getObject(
            Notificationtype::class
        );
    }

    public function testToOptionArray()
    {
        $this->assertEquals(
            [
                ['value' => 'sms', 'label' => 'SMS'],
                ['value' => 'email', 'label' => 'Email'],
                ['value' => 'smsemail', 'label' => 'SMS and Email Both'],
            ],
            $this->object->toOptionArray()
        );
    }
}
