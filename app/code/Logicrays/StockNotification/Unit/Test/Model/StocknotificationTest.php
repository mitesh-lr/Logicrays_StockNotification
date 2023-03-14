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

namespace Logicrays\StockNotification\Unit\Test\Model;

use Logicrays\StockNotification\Api\Data\StockNotificationInterface;
use Magento\Framework\Model\AbstractExtensibleModel;
use Logicrays\StockNotification\Model\ResourceModel\StockNotification as StocknotificationResource;
use Logicrays\StockNotification\Model\StockNotification;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use \Magento\Framework\Registry;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;

/**
 * Unit test for model file.
 */
class StocknotificationTest extends TestCase
{
    /**
     * @var Stocknotification
     */
    private Stocknotification $model;
    /**
     * @var Context&MockObject
     */
    private $contextMock;
    /**
     * @var StocknotificationResource&MockObject
     */
    private $resourceMock;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->contextMock = $this->getMockBuilder(\Magento\Framework\Model\Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->resourceMock = $this->getMockBuilder(StocknotificationResource::class)
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager = new ObjectManager($this);

        $this->model = $objectManager->getObject(
            Stocknotification::class,
            [
                'context' => $this->contextMock,
                'resource' => $this->resourceMock,
            ]
        );
    }

    /**
     * @return void
     */
    public function testGetEntityId()
    {
        $this->model->getEntityId();
    }

    /**
     * @return void
     */
    public function testSetEntityId()
    {
        $entityId = '1';
        $this->model->setEntityId($entityId);
    }

    /**
     * @return void
     */
    public function testGetWebsitename()
    {
        $this->model->getWebsitename();
    }

    /**
     * @return void
     */
    public function testSetWebsitename()
    {
        $websitename = 'Main Website';
        $this->model->setWebsitename($websitename);
    }

    /**
     * @return void
     */
    public function testGetProductId()
    {
        $this->model->getProductId();
    }

    /**
     * @return void
     */
    public function testSetProductId()
    {
        $product_id = '1';
        $this->model->setProductId($product_id);
    }

    /**
     * @return void
     */
    public function testGetProductName()
    {
        $this->model->getProductName();
    }

    /**
     * @return void
     */
    public function testSetProductName()
    {
        $product_name = 'test';
        $this->model->setProductName($product_name);
    }

    /**
     * @return void
     */
    public function testGetProductSku()
    {
        $this->model->getProductSku();
    }

    /**
     * @return void
     */
    public function testSetProductSku()
    {
        $product_sku = 'test';
        $this->model->setProductSku($product_sku);
    }

    /**
     * @return void
     */
    public function testGetSubscriberName()
    {
        $this->model->getSubscriberName();
    }

    /**
     * @return void
     */
    public function testSetSubscriberName()
    {
        $subscriber_name = 'test';
        $this->model->setSubscriberName($subscriber_name);
    }

    /**
     * @return void
     */
    public function testGetSubscriberEmail()
    {
        $this->model->getSubscriberEmail();
    }

    /**
     * @return void
     */
    public function testSetSubscriberEmail()
    {
        $subscriber_email = 'test@gmail.com';
        $this->model->setSubscriberEmail($subscriber_email);
    }

    /**
     * @return void
     */
    public function testGetSubscriberMobile()
    {
        $this->model->getSubscriberMobile();
    }

    /**
     * @return void
     */
    public function testSetSubscriberMobile()
    {
        $subscriber_mobile = '1234567898';
        $this->model->setSubscriberMobile($subscriber_mobile);
    }

    /**
     * @return void
     */
    public function testGetCreatedAt()
    {
        $this->model->getCreatedAt();
    }

    /**
     * @return void
     */
    public function testSetCreatedAt()
    {
        $createdAt = '2022-10-27 07:00:00';
        $this->model->setCreatedAt($createdAt);
    }
}
