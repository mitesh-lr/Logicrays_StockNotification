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

namespace Logicrays\StockNotification\Cron;

use Psr\Log\LoggerInterface;
use Logicrays\StockNotification\Model\ResourceModel\StockNotification\CollectionFactory;
use Logicrays\StockNotification\Helper\Apicall;
use Logicrays\StockNotification\Model\StockNotification;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Logicrays\StockNotification\Helper\Data;
use Logicrays\StockNotification\Helper\Email;
use Logicrays\StockNotification\Model\Productnotification;

/**
 * Send the notification based on cron run.
 */
class SendNotification
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var CollectionFactory
     */
    protected $notificationsSearchResults;

    /**
     * @var StockNotification
     */
    protected $_stockNotification;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var Apicall
     */
    protected $_helperapi;

    /**
     * @var Data
     */
    protected $_helper;

    /**
     * @var Email
     */
    protected $emailHelper;

    /**
     * @var Productnotification
     */
    protected $productnotification;

    /**
     * @param LoggerInterface $logger
     * @param CollectionFactory $notificationsSearchResults
     * @param Apicall $helperapi
     * @param StockNotification $stockNotification
     * @param ProductRepositoryInterface $productRepository
     * @param Data $helper
     * @param Email $emailHelper
     * @param Productnotification $productnotification
     */
    public function __construct(
        LoggerInterface $logger,
        CollectionFactory $notificationsSearchResults,
        Apicall $helperapi,
        StockNotification $stockNotification,
        ProductRepositoryInterface $productRepository,
        Data $helper,
        Email $emailHelper,
        Productnotification $productnotification
    ) {
        $this->logger = $logger;
        $this->notificationsSearchResults = $notificationsSearchResults;
        $this->_stockNotification = $stockNotification;
        $this->_helper = $helper;
        $this->productRepository = $productRepository;
        $this->emailHelper = $emailHelper;
        $this->_helperapi = $helperapi;
        $this->productnotification = $productnotification;
    }

    /**
     * Get product back in stock email
     *
     * @return void
     */
    public function productBackInStockEmail()
    {
        $collection = $this->_stockNotification->getCollection();
        $pending = $collection->addFieldToFilter('notification_status', 'Pending');

        foreach ($pending as $key => $pendingdata) {
            $email = $pendingdata->getSubscriberEmail();
            $pid = $pendingdata->getProductId();
            if ($this->_helper->getStockNotificationType() == 'email' ||
                $this->_helper->getStockNotificationType() == 'smsemail') {
                $this->emailHelper->sendBackInStockEmail($email, $pendingdata->getProductId());
                $status = $pendingdata->setNotificationStatus("Send")->save();

                $ProductNotification = $this->productnotification;
                $ProductNotificationCollection = $ProductNotification->getCollection();
                $allproductcollection = $ProductNotificationCollection->addFieldToFilter('product_id', $pid);

                foreach ($allproductcollection as $key => $collectionvalue) {
                    $id_post_update = $collectionvalue->getEntityId();
                    $pendingnotification = $collectionvalue->getPendingNotification() - 1;
                }
                $postUpdate = $ProductNotification->load($id_post_update);
                $postUpdate->setPendingNotification($pendingnotification);
                $postUpdate->save();
            }
            if ($this->_helper->getStockNotificationType() == 'sms' ||
                $this->_helper->getStockNotificationType() == 'smsemail') {
                $mobile = $pendingdata->getSubscriberMobile();
                $this->_emailfilter->setVariables([
                        'mobilenumber' => $pendingdata->getSubscriberMobile(),
                        'product' => $pendingdata->getProductName()
                ]);
                $message = $this->_helper->getCustomerTemplateForUser();
                $finalmessage = $this->_emailfilter->filter($message);
                $this->_helperapi->callApi($mobile, $finalmessage);
            }
        }
    }

    /**
     * Send notification
     *
     * @return void
     */
    public function execute()
    {
        $notifications = $this->notificationsSearchResults->create();
        foreach ($notifications as $notification) {
            $product = $this->productRepository->getById($notification->getProductId());
            if ($product->isInStock()) {
                try {
                    $this->productBackInStockEmail();
                } catch (\Excepton $e) {
                    $this->logger->error($e->getMessage());
                }
            }
        }
    }
}
