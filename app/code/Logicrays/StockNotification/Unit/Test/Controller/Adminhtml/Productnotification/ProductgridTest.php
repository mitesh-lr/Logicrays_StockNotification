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

namespace Logicrays\StockNotification\Unit\Test\Controller\Adminhtml\Productnotification;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Logicrays\StockNotification\Controller\Adminhtml\Productnotification\Productgrid;
use Magento\Framework\Authorization;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for product grid.
 */
class ProductgridTest extends TestCase
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var PageFactory|MockObject
     */
    protected $pageFactory;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var Authorization|MockObject
     */
    protected $authorizationMock;

    protected function setUp(): void
    {
        $this->context = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->pageFactory = $this->getMockBuilder(PageFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create','setActiveMenu','getConfig','getTitle','prepend','isAllowed'])
            ->getMock();
        $this->stocknotification = new Productgrid(
            $this->context,
            $this->pageFactory
        );
    }

    public function testExecute()
    {
        $this->pageFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->pageFactory);
        $this->pageFactory->expects($this->once())
            ->method('setActiveMenu')
            ->willReturn($this->pageFactory);
        $this->pageFactory->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->pageFactory);
        $this->pageFactory->expects($this->once())
            ->method('getTitle')
            ->willReturn($this->pageFactory);
        $this->pageFactory->expects($this->once())
            ->method('prepend')
            ->willReturn($this->pageFactory);
        $this->stocknotification->execute();
    }
}
