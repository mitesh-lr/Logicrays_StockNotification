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
namespace Logicrays\StockNotification\Unit\Test\ViewModel;

use Logicrays\StockNotification\ViewModel\OutOfStock;
use Magento\Store\Model\StoreManagerInterface;
use Logicrays\StockNotification\Helper\Data;
use Magento\Customer\Model\Session;
use Logicrays\StockNotification\Model\StockNotification;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for view model file.
 */
class OutOfStockTest extends TestCase
{
    /**
     * @var StoreManagerInterface
     */
    private $storemanagerinterface;

    /**
     * @var Data|MockObject
     */
    private $helperData;

    /**
     * @var Session|MockObject
     */
    private $customerSession;

    /**
     * @var Stocknotification|MockObject
     */
    private $stocknotification;

    /**
     * @var OutOfStock
     */
    private $viewModel;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    protected function setUp(): void
    {
        $this->data = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();
        $objectManager = new ObjectManager($this);
        $this->viewModel = $objectManager->getObject(
            OutOfStock::class,
            [
                'data' => $this->data,
            ]
        );
    }

    /**
     * @return void
     */
    public function testGetHelperData(): void
    {
        $this->viewModel->getHelperData();
    }

    /**
     * @return void
     */
    public function testGetCustomerData()
    {
        $this->viewModel->getCustomerData();
    }

    /**
     * @return void
     */
    public function testGetStocknotificationRowData()
    {
        $this->viewModel->getStocknotificationRowData();
    }
}
