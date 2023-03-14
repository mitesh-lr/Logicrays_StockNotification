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

namespace Logicrays\StockNotification\Block\Adminhtml\StockNotification\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
use Logicrays\StockNotification\Model\Status;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\UrlInterface;
use Logicrays\StockNotification\Block\Adminhtml\StockNotification\Edit\Form;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit test fro form.
 */
class FormTest extends TestCase
{
    /**
     * @var Form
     */
    private $form;

    /**
     * @var Context|MockObject
     */
    private $context;

    /**
     * @var Registry|MockObject
     */
    private $registry;

    /**
     * @var FormFactory|MockObject
     */
    private $formFactory;

    /**
     * @var Source|MockObject
     */
    private $rateSource;

    /**
     * @var TaxRuleRepositoryInterface|MockObject
     */
    private $taxRuleRepository;

    /**
     * @var TaxClassRepositoryInterface|MockObject
     */
    private $taxClassRepository;

    /**
     * @var Customer|MockObject
     */
    private $taxClassCustomer;

    /**
     * @var Product|MockObject
     */
    private $product;

    /**
     * @var UrlInterface|MockObject
     */
    private $urlBuilder;

    protected function setUp(): void
    {
        $objectManagerHelper = new ObjectManager($this);

        $this->context = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->registry = $this->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->formFactory = $this->getMockBuilder(FormFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->config = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->status = $this->getMockBuilder(Status::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->urlBuilder = $this->getMockBuilder(UrlInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->form = $objectManagerHelper->getObject(Form::class, [
            'context' => $this->context,
            'registry' => $this->registry,
            'formFactory' => $this->formFactory,
            '_urlBuilder' => $this->urlBuilder
        ]);
    }
    public function testPrepareForm()
    {
    }
}
