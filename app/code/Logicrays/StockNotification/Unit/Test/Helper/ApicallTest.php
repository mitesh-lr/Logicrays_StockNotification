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

use Magento\Framework\App\Helper\Context;
use Magento\Framework\HTTP\Client\Curl;
use Logicrays\StockNotification\Helper\Apicall;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for api call file.
 */
class ApicallTest extends TestCase
{
    protected function setUp(): void
    {
        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->curl = $this->getMockBuilder(Curl::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->scopeConfigInterface = $this->getMockBuilder(ScopeConfigInterface::class)
            ->onlyMethods(['getValue'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->apicall = new Apicall(
            $this->contextMock,
            $this->curl,
            $this->scopeConfigInterface
        );
    }

    public function testGetApiSenderId()
    {
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Apicall::SMS_MSG91_API_SENDERID, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->apicall->getApiSenderId();
    }

    public function testGetAuthKey()
    {
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Apicall::SMS_MSG91_API_AUTHKEY, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->apicall->getAuthKey();
    }

    public function testGetAdminNumber()
    {
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Apicall::OUTOFSTOCK_NOTIFICATION_SMS_ADMIN_NUMBER, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->apicall->getAdminNumber();
    }

    public function testGetApiUrl()
    {
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Apicall::SMS_MSG91_API_URL, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->apicall->getApiUrl();
    }

    public function testValidateSmsConfig()
    {
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Apicall::SMS_MSG91_API_URL, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Apicall::SMS_MSG91_API_AUTHKEY, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Apicall::SMS_MSG91_API_SENDERID, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->apicall->validateSmsConfig();
    }

    public function testGetOutofstockNotificationUserTemplate()
    {
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Apicall::OUTOFSTOCK_NOTIFICATION_SMS_CUSTOMER, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->apicall->getOutofstockNotificationUserTemplate();
    }

    public function testIsOutofstockNotificationForAdmin()
    {
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Apicall::OUTOFSTOCK_NOTIFICATION_SMS_ADMIN, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->apicall->isOutofstockNotificationForAdmin();
    }

    public function testGetOutofstockAdminEnable()
    {
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Apicall::OUTOFSTOCK_NOTIFICATION_SMS_ADMIN_ENABLE, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->apicall->getOutofstockAdminEnable();
    }

    public function testCallApiAdmin()
    {
        $adminmobile = 7785568985;
        $finalmessage = "test";
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Apicall::SMS_MSG91_API_URL, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Apicall::SMS_MSG91_API_AUTHKEY, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Apicall::SMS_MSG91_API_SENDERID, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->apicall->callApiAdmin($adminmobile, $finalmessage);
    }
}
