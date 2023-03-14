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

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Logicrays\StockNotification\Helper\Email;
use Magento\Store\Model\ScopeInterface;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for sending email file.
 */
class EmailTest extends TestCase
{
    protected function setUp(): void
    {
        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->scopeConfigInterface = $this->getMockBuilder(ScopeConfigInterface::class)
            ->onlyMethods(['getValue'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->stateInterface = $this->getMockBuilder(StateInterface::class)
            ->onlyMethods(['suspend'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->transportBuilder = $this->getMockBuilder(TransportBuilder::class)
            ->onlyMethods(['setTemplateIdentifier'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeManagerInterface = $this->getMockBuilder(StoreManagerInterface::class)
            ->addMethods(['getId','getStoreId'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->productRepositoryInterface = $this->getMockBuilder(ProductRepositoryInterface::class)
            ->onlyMethods(['getById'])
            ->addMethods(['getName','getUrl'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->email = new Email(
            $this->contextMock,
            $this->scopeConfigInterface,
            $this->stateInterface,
            $this->transportBuilder,
            $this->storeManagerInterface,
            $this->productRepositoryInterface
        );
    }

    public function testGetStorename(): void
    {
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Email::TRANS_NAME, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->assertTrue($this->email->getStorename());
    }

    public function testGetNotificationAdminEmail(): void
    {
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Email::OUTOFSTOCK_NOTIFICATION_ADMIN_EMAIL, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->assertTrue($this->email->getNotificationAdminEmail());
    }

    public function testGetStoreEmail(): void
    {
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Email::TRANS_EMAIL, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->assertTrue($this->email->getStoreEmail());
    }

    public function testGetFromArray(): void
    {
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Email::TRANS_EMAIL, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Email::TRANS_NAME, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->email->getFromArray();
    }

    public function testGetTemplateOptionsArray(): void
    {
        $this->storeManagerInterface->expects($this->any())
            ->method('getStore')
            ->willReturn($this->storeManagerInterface);
        $this->storeManagerInterface->expects($this->once())
            ->method('getId')
            ->willReturn($this->storeManagerInterface);
        $this->email->getTemplateOptionsArray();
    }

    public function testSendSubscribedEmail(): void
    {
        $email = "test@gmail.com";
        $productId = 1;
        $storeId = 1;
        $this->productRepositoryInterface->expects($this->once())
            ->method('getById')
            ->willReturn($this->productRepositoryInterface);
        $this->productRepositoryInterface->expects($this->once())
            ->method('getName')
            ->willReturn($this->productRepositoryInterface);
        $this->productRepositoryInterface->expects($this->once())
            ->method('getUrl')
            ->willReturn($this->productRepositoryInterface);
        $this->stateInterface->expects($this->once())
            ->method('suspend')
            ->willReturn($this->stateInterface);
        $this->storeManagerInterface->expects($this->any())
            ->method('getStore')
            ->willReturn($this->storeManagerInterface);
        $this->storeManagerInterface->expects($this->once())
            ->method('getStoreId')
            ->willReturn($this->storeManagerInterface);
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Email::EMAIL_TEMPLATE, ScopeInterface::SCOPE_STORE, $storeId)
            ->will($this->returnValue(true));
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Email::TRANS_EMAIL, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Email::TRANS_NAME, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->transportBuilder->expects($this->any())
            ->method('setTemplateIdentifier')
            ->with($this->scopeConfigInterface)
            ->willReturn($this->transportBuilder);
        $this->email->sendSubscribedEmail($email, $productId);
    }

    public function testSendBackInStockEmail(): void
    {
        $email = "test@gmail.com";
        $productId = 1;
        $storeId = 1;
        $this->productRepositoryInterface->expects($this->once())
            ->method('getById')
            ->willReturn($this->productRepositoryInterface);
        $this->storeManagerInterface->expects($this->any())
            ->method('getStore')
            ->willReturn($this->storeManagerInterface);
        $this->storeManagerInterface->expects($this->once())
            ->method('getStoreId')
            ->willReturn($this->storeManagerInterface);
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Email::TRANS_EMAIL, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Email::TRANS_NAME, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->scopeConfigInterface->expects($this->any())->method('getValue')
            ->with(Email::EMAIL_TEMPLATE_INSTOCK, ScopeInterface::SCOPE_STORE)
            ->will($this->returnValue(true));
        $this->email->sendBackInStockEmail($email, $productId);
    }
}
