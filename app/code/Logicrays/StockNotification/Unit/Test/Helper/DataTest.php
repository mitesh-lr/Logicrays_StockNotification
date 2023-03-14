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

namespace Logicrays\StockNotification\Unit\Test\Helper;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Logicrays\StockNotification\Helper\Data;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\Website;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Unit test for data file.
 */
class DataTest extends TestCase
{
    /**
     * @var ScopeConfigInterface|MockObject
     */
    protected $scopeConfigInterface;

    /**
     * @var Data|MockObject
     */
    private $helper;

    protected function setUp(): void
    {
        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->scopeConfigInterface = $this->getMockBuilder(ScopeConfigInterface::class)
            ->onlyMethods(['getValue'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->helper = new Data(
            $this->contextMock,
            $this->scopeConfigInterface
        );
    }

    /**
     * Check Module Is enable or not
     *
     * @return bool
     */
    public function testIsEnabled(): void
    {
        $this->scopeConfigInterface->expects($this->once())->method('getValue')
            ->with(Data::MODULE_ENABLE, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->assertTrue($this->helper->isEnabled());
    }

    public function testGetNotificationTitle(): void
    {
        $this->scopeConfigInterface->expects($this->once())->method('getValue')
            ->with(Data::OUTOFSTOCK_NOTIFICATION_TITLE, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->assertTrue($this->helper->getNotificationTitle());
    }

    public function testGetNotificationSucsess(): void
    {
        $this->scopeConfigInterface->expects($this->once())->method('getValue')
            ->with(Data::OUTOFSTOCK_NOTIFICATION_SUCSESS, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->assertTrue($this->helper->getNotificationSucsess());
    }

    public function testGetNotificationButton(): void
    {
        $this->scopeConfigInterface->expects($this->once())->method('getValue')
            ->with(Data::OUTOFSTOCK_NOTIFICATION_BUTTON_TITLE, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->assertTrue($this->helper->getNotificationButton());
    }

    public function testGetCustomerGroup(): void
    {
        $this->scopeConfigInterface->expects($this->once())->method('getValue')
            ->with(Data::OUTOFSTOCK_NOTIFICATION_CUSTOMERGROUP, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->assertTrue($this->helper->getCustomerGroup());
    }

    public function testGetStockNotificationType(): void
    {
        $this->scopeConfigInterface->expects($this->once())->method('getValue')
            ->with(Data::OUTOFSTOCK_NOTIFICATION_TYPE, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->assertTrue($this->helper->getStockNotificationType());
    }

    public function testGetStockNotificationSender(): void
    {
        $this->scopeConfigInterface->expects($this->once())->method('getValue')
            ->with(Data::OUTOFSTOCK_NOTIFICATION_SENDER, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->assertTrue($this->helper->getStockNotificationSender());
    }

    public function testGetStockNotificationTemplate(): void
    {
        $this->scopeConfigInterface->expects($this->once())->method('getValue')
            ->with(Data::OUTOFSTOCK_NOTIFICATION_EMAIL_TEMPLATE, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->assertTrue($this->helper->getStockNotificationTemplate());
    }
}
