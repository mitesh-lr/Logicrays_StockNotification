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

namespace Logicrays\StockNotification\Unit\Test\Cron;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Logicrays\StockNotification\Cron\SendNotification;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\Website;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Logicrays\StockNotification\Model\ResourceModel\StockNotification\CollectionFactory;
use Logicrays\StockNotification\Helper\Apicall;
use Logicrays\StockNotification\Model\StockNotification;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Logicrays\StockNotification\Helper\Data;
use Logicrays\StockNotification\Helper\Email;
use Logicrays\StockNotification\Model\Productnotification;

/**
 * Unit test for send notification file based on cron job.
 */
class SendNotificationTest extends TestCase
{
    /**
     * @var ScopeConfigInterface|MockObject
     */
    protected $scopeConfigInterface;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    protected function setUp(): void
    {
        $this->logger = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->collectionFactory = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->addMethods(['getById'])
            ->getMock();

        $this->apicall = $this->getMockBuilder(Apicall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->stocknotification = $this->getMockBuilder(Stocknotification::class)
            ->disableOriginalConstructor()
            ->addMethods(['create','addFieldToFilter'])
            ->onlyMethods(['getCollection','load'])
            ->getMock();

        $this->productRepositoryInterface = $this->getMockBuilder(ProductRepositoryInterface::class)
            ->addMethods(['isInStock'])
            ->getMockForAbstractClass();

        $this->data = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->email = $this->getMockBuilder(Email::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productnotification = $this->getMockBuilder(Productnotification::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCollection','addFieldToFilter'])
            ->getMock();

        $this->sendNotification = $this->getMockBuilder(SendNotification::class)
            ->disableOriginalConstructor()
            ->setMethods(['addFieldToFilter'])
            ->getMock();

        $this->cron = new SendNotification(
            $this->logger,
            $this->collectionFactory,
            $this->apicall,
            $this->stocknotification,
            $this->productRepositoryInterface,
            $this->data,
            $this->email,
            $this->productnotification,
            $this->sendNotification
        );
    }

    /**
     * @return void
     */
    public function testExecute()
    {
        $this->stocknotification->expects(self::any())
            ->method('load')->willReturnSelf();
        $itemArray = [0 => $this->stocknotification];

        $this->collectionFactory->expects(self::any())
            ->method('create')->willReturn($itemArray);

        $this->collectionFactory->expects(self::any())
            ->method('getById')->willReturn($this->stocknotification);

        $this->productRepositoryInterface->expects($this->once())
            ->method('isInStock')->willReturn($this->productRepositoryInterface);

        $this->stocknotification->expects(self::any())
            ->method('getCollection')->willReturn($this->stocknotification);
        $this->stocknotification->expects(self::any())
            ->method('addFieldToFilter')->willReturn($this->stocknotification);
        $this->productnotification->expects(self::any())
            ->method('getCollection')->willReturn($this->productnotification);
        $this->productnotification->expects(self::any())
            ->method('addFieldToFilter')->willReturn($this->productnotification);
        $this->assertTrue($this->cron->isInStock());
        $this->cron->execute();
    }

    public function testProductBackInStockEmail(): void
    {
        $this->stocknotification->expects($this->any())
            ->method('load')->willReturnSelf();
        $itemArray = [0 => $this->stocknotification];
        $this->stocknotification->expects($this->any())
            ->method('getCollection')->willReturn($itemArray);
    }
}
