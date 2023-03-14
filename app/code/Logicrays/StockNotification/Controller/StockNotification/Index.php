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

namespace Logicrays\StockNotification\Controller\StockNotification;

use Magento\Framework\App\Action\Action;
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

/**
 * Send the notification from frontend.
 */
class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var StockNotification
     */
    protected $stockNotification;

    /**
     * @var Productnotification
     */
    protected $productnotification;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var Data
     */
    protected $_helper;

    /**
     * @var Apicall
     */
    protected $_helperapi;

    /**
     * @var Email
     */
    protected $emailHelper;
    
    /**
     * @var Session
     */
    protected $_customerSession;

    /**
     * @var Filter
     */
    protected $emailfilter;
   
    /**
     * @param Context $context
     * @param Session $customerSession
     * @param JsonFactory $resultJsonFactory
     * @param StockNotification $stockNotification
     * @param Productnotification $productnotification
     * @param Data $helper
     * @param Apicall $helperapi
     * @param StoreManagerInterface $storeManager
     * @param Email $emailHelper
     * @param Filter $filter
     * @param ScopeConfigInterface $configScopeConfigInterface
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        JsonFactory $resultJsonFactory,
        StockNotification $stockNotification,
        Productnotification $productnotification,
        Data $helper,
        Apicall $helperapi,
        StoreManagerInterface $storeManager,
        Email $emailHelper,
        Filter $filter,
        ScopeConfigInterface $configScopeConfigInterface,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->stockNotification = $stockNotification;
        $this->productnotification = $productnotification;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_customerSession = $customerSession;
        $this->_helperapi = $helperapi;
        $this->_helper = $helper;
        $this->emailHelper = $emailHelper;
        $this->scopeConfig = $configScopeConfigInterface;
        $this->_storeManager = $storeManager;
        $this->emailfilter = $filter;
        parent::__construct($context);
    }

    /**
     * Out of stock product subscribtion action
     *
     * @return ResultFactory
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        if ($params['mobile'] == ' ') {
            $this->messageManager->addError(__('You Have Already Subscribe this Product'));
            return;
        }
        $sucsess = "Thanks for your interest, we will notify you when the Product becomes available";
        if ($this->_helper->getNotificationSucsess()) {
            $sucsess = $this->_helper->getNotificationSucsess();
        }
        if ($this->_customerSession->isLoggedIn()) {
            $customername = $this->_customerSession->getCustomer()->getName();
        } else {
            $customername = "Guest";
        }
        $websitename = $this->_storeManager->getWebsite()->getName();
        $rowData = $this->stockNotification;
        $collection = $rowData->getCollection();
        $pending = $collection->addFieldToFilter('notification_status', 'Pending');
        $email = [];
        foreach ($pending as $key => $pendingdata) {
                $email[] = $pendingdata->getSubscriberEmail();
        }
        if (in_array($params['email'], $email)) {
            $match = $collection->addFieldToFilter('subscriber_email', $params['email']);
            foreach ($match as $key => $matchvalue) {
                  $product_id = $matchvalue->getProductId();
            }
            if ($product_id == $params['product_id']) {
                  $this->messageManager->addError(__('You Have Already Subscribe this Product'));
            } else {
                $rowData->setProductId($params['product_id'])
                    ->setProductName($params['product_name'])
                    ->setProductSku($params['product_sku'])
                    ->setWebsitename($websitename)
                    ->setSubscriberName($customername)
                    ->setSubscriberEmail($params['email'])
                    ->setSubscriberMobile($params['mobile'])
                    ->save();
                    $this->messageManager->addSuccess(__($sucsess));
                $ProductNotification = $this->productnotification;
                $ProductNotificationCollection = $ProductNotification->getCollection();
                $allproductcollection = $ProductNotificationCollection->
                addFieldToFilter('product_id', $params['product_id']);

                foreach ($allproductcollection as $key => $collectionvalue) {
                      $pid = $collectionvalue->getProductId();
                }

                if (isset($pid)) {
                    if ($pid == $params['product_id']) {
                        $id_post_update = $collectionvalue->getEntityId();
                        $totalnotification = $collectionvalue->getTotalNotification() + 1 ;
                        $pendingnotification = $collectionvalue->getPendingNotification() + 1;
                    } else {
                        $totalnotification = 1;
                        $pendingnotification = 1;
                    }
                    $postUpdate = $ProductNotification->load($id_post_update);
                    $postUpdate->setTotalNotification($totalnotification);
                    $postUpdate->setPendingNotification($pendingnotification);
                    $postUpdate->save();
                } else {
                    $totalnotification = 1;
                    $pendingnotification = 1;
                    $ProductNotification->setProductId($params['product_id'])
                        ->setProductName($params['product_name'])
                        ->setProductSku($params['product_sku'])
                        ->setTotalNotification($totalnotification)
                        ->setPendingNotification($pendingnotification)
                        ->save();
                }

                if ($this->_helper->getStockNotificationType() == 'sms' ||
                $this->_helperapi->getOutofstockAdminEnable() ||
                $this->_helper->getStockNotificationType() == 'smsemail') {
                          $adminmobile = $this->_helperapi->getAdminNumber();
                          $this->emailfilter->setVariables([
                                  'product' => $params['product_name'],
                                  'customername' => $customername
                              ]);
                          $message = $this->_helperapi->isOutofstockNotificationForAdmin();
                          $finalmessage = $this->emailfilter->filter($message);
                          $this->_helperapi->callApiAdmin($adminmobile, $finalmessage);
                }
            
                if ($this->_helper->getStockNotificationType() == 'email' ||
                  $this->_helper->getStockNotificationType() == 'smsemail') {
                      $this->emailHelper->sendSubscribedEmail($params['email'], $params['product_id']);
                }
            }
        } else {
            $rowData->setProductId($params['product_id'])
                ->setProductName($params['product_name'])
                ->setProductSku($params['product_sku'])
                ->setWebsitename($websitename)
                ->setSubscriberName($customername)
                ->setSubscriberEmail($params['email'])
                ->setSubscriberMobile($params['mobile'])
                ->save();
                $this->messageManager->addSuccess(__($sucsess));

            $ProductNotification = $this->productnotification;
            $ProductNotificationCollection = $ProductNotification->getCollection();
            $allproductcollection = $ProductNotificationCollection->
            addFieldToFilter('product_id', $params['product_id']);

            foreach ($allproductcollection as $key => $collectionvalue) {
                  $pid = $collectionvalue->getProductId();
            }

            $totalnotification = 1;
            $pendingnotification = 1;

            if (isset($pid)) {
                if ($pid == $params['product_id']) {
                    $totalnotification = $collectionvalue->getTotalNotification() + 1 ;
                    $pendingnotification = $collectionvalue->getPendingNotification() + 1;
                    $id_post_update = $collectionvalue->getEntityId();
                    $postUpdate = $ProductNotification->load($id_post_update);
                    $postUpdate->setTotalNotification($totalnotification);
                    $postUpdate->setPendingNotification($pendingnotification);
                    $postUpdate->save();
                } else {
                    $totalnotification = 1;
                    $pendingnotification = 1;
                }
            } else {
                    $ProductNotification->setProductId($params['product_id'])
                        ->setProductName($params['product_name'])
                        ->setProductSku($params['product_sku'])
                        ->setTotalNotification($totalnotification)
                        ->setPendingNotification($pendingnotification)
                        ->save();
            }

            if ($this->_helper->getStockNotificationType() == 'sms' ||
            $this->_helperapi->getOutofstockAdminEnable() ||
            $this->_helper->getStockNotificationType() == 'smsemail') {
                  $adminmobile = $this->_helperapi->getAdminNumber();
                  $this->emailfilter->setVariables([
                          'product' => $params['product_name'],
                          'customername' => $customername
                      ]);
                  $message = $this->_helperapi->isOutofstockNotificationForAdmin();
                  $finalmessage = $this->emailfilter->filter($message);
                  $this->_helperapi->callApiAdmin($adminmobile, $finalmessage);
            }

            if ($this->_helper->getStockNotificationType() == 'email' ||
            $this->_helper->getStockNotificationType() == 'smsemail') {
                  $this->emailHelper->sendSubscribedEmail($params['email'], $params['product_id']);
            }
        }
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}
