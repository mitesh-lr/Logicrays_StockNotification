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

namespace Logicrays\StockNotification\Unit\Test\Model\Config\Source\Customer;

use Magento\Customer\Model\ResourceModel\Group\CollectionFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Logicrays\StockNotification\Model\Config\Source\Customer\Group;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for customer group render file.
 */
class GroupTest extends TestCase
{
    /** @var CollectionFactory */
    protected $collectionFactory;

    /** @var ObjectManager */
    protected $objectManager;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->collectionFactory = $this->getMockBuilder(CollectionFactory::class)
            ->onlyMethods(['create'])
            ->addMethods(['loadData','toOptionArray'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->group = new Group(
            $this->collectionFactory,
        );
    }

    public function testToOptionArray()
    {
        $this->collectionFactory->expects(self::once())
            ->method('create')->willReturn($this->collectionFactory);
        $this->collectionFactory->expects(self::once())
            ->method('loadData')->willReturn($this->collectionFactory);
        $this->collectionFactory->expects(self::once())
            ->method('toOptionArray')->willReturn($this->collectionFactory);
        $this->group->toOptionArray();
    }
}
