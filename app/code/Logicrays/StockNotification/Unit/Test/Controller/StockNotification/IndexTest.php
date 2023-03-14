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

namespace Logicrays\StockNotification\Unit\Test\Controller\StockNotification;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Controller\Result\JsonFactory;
use Logicrays\StockNotification\Model\StockNotification;
use Logicrays\StockNotification\Model\Productnotification;
use Logicrays\StockNotification\Helper\Data;
use Logicrays\StockNotification\Helper\Apicall;
use Magento\Store\Model\StoreManagerInterface;
use Logicrays\StockNotification\Helper\Email;
use Magento\Email\Model\Template\Filter;
use Magento\Framework\View\Result\PageFactory;
use Logicrays\StockNotification\Controller\StockNotification\Index;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\ViewInterface;
use Magento\Framework\DataObject;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\View\Layout;
use Magento\Framework\View\Layout\ProcessorInterface;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Result\Page;
use Magento\Store\Model\Store;
use PHPUnit\Framework\MockObject\MockObject;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Request\Http;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for frontend side render file.
 */
class IndexTest extends TestCase
{
    /**
     * @var RequestInterface|MockObject
     */
    protected $request;

    /**
     * @var ResponseInterface|MockObject
     */
    protected $response;

    /**
     * @var Category|MockObject
     */
    protected $categoryHelper;

    /**
     * @var ObjectManagerInterface|MockObject
     */
    protected $objectManager;

    /**
     * @var ManagerInterface|MockObject
     */
    protected $eventManager;

    /**
     * @var \Magento\Framework\View\Layout|MockObject
     */
    protected $layout;

    /**
     * @var ProcessorInterface|MockObject
     */
    protected $update;

    /**
     * @var ViewInterface|MockObject
     */
    protected $view;

    /**
     * @var Context|MockObject
     */
    protected $context;

    /**
     * @var \Magento\Catalog\Model\Category|MockObject
     */
    protected $category;

    /**
     * @var CategoryRepositoryInterface|MockObject
     */
    protected $categoryRepository;

    /**
     * @var Store|MockObject
     */
    protected $store;

    /**
     * @var StoreManagerInterface|MockObject
     */
    protected $storeManager;

    /**
     * @var Design|MockObject
     */
    protected $catalogDesign;

    /**
     * @var View
     */
    protected $action;

    /**
     * @var ResultFactory|MockObject
     */
    protected $resultFactory;

    /**
     * @var \Magento\Framework\View\Page|MockObject
     */
    protected $page;

    /**
     * @var Config
     */
    protected $pageConfig;

    /**
     * @var ManagerInterface|MockObject
     */
    private $messageManagerMock;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->request = $this->getMockForAbstractClass(RequestInterface::class);

        $this->requestMock = $this->getMockBuilder(Http::class)
        ->disableOriginalConstructor()
        ->setMethods(['getParams'])
        ->getMock();

        $this->response = $this->getMockForAbstractClass(ResponseInterface::class);

        $this->objectManager = $this->getMockForAbstractClass(ObjectManagerInterface::class);
        $this->eventManager = $this->getMockForAbstractClass(ManagerInterface::class);

        $this->update = $this->getMockForAbstractClass(ProcessorInterface::class);
        $this->layout = $this->createMock(Layout::class);
        $this->layout->expects($this->any())->method('getUpdate')->willReturn($this->update);

        $this->session = $this->getMockBuilder(Session::class)
            ->setMethods(['isLoggedIn'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->jsonFactory = $this->getMockBuilder(JsonFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->stocknotification = $this->getMockBuilder(Stocknotification::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productnotification = $this->getMockBuilder(Productnotification::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->data = $this->getMockBuilder(Data::class)
            ->setMethods(['getValue'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->apicall = $this->getMockBuilder(Apicall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->email = $this->getMockBuilder(Email::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->filter = $this->getMockBuilder(Filter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->scopeConfigInterface = $this->getMockBuilder(ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->messageManagerMock = $this->getMockBuilder(ManagerInterface::class)
            ->getMockForAbstractClass();

        $this->context = $this->createMock(Context::class);
        $this->context->expects($this->any())->method('getRequest')->willReturn($this->requestMock);
        $this->context->expects($this->any())->method('getResponse')->willReturn($this->response);
        $this->context->expects($this->any())->method('getObjectManager')
            ->willReturn($this->objectManager);
        $this->context->expects($this->any())->method('getEventManager')->willReturn($this->eventManager);
        $this->context->expects($this->any())->method('getView')->willReturn($this->view);
        $this->context->expects($this->any())->method('getResultFactory')
            ->willReturn($this->resultFactory);

        $this->context->expects($this->any())
            ->method('getMessageManager')
            ->willReturn($this->messageManagerMock);

        $this->store = $this->createMock(Store::class);
        $this->storeManager = $this->getMockForAbstractClass(StoreManagerInterface::class);
        $this->storeManager->expects($this->any())->method('getStore')->willReturn($this->store);

        $resultPageFactory = $this->getMockBuilder(PageFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->getMock();

        $this->scopeConfigInterface = $this->getMockBuilder(ScopeConfigInterface::class)
            ->onlyMethods(['getValue'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->action = (new ObjectManager($this))->getObject(
            Index::class,
            [
                'context' => $this->context,
                'storeManager' => $this->storeManager,
                'resultPageFactory' => $resultPageFactory,
                'data' => $this->data
            ]
        );
    }

    public function testExecute()
    {
        $params = ['mobile' => ' '];

        $this->requestMock->expects($this->once())
            ->method('getParams')
            ->willReturn($params);

        $this->messageManagerMock->expects($this->once())
            ->method('addError')
            ->with(__('You Have Already Subscribe this Product'));
        $this->action->execute();
    }
}
