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

namespace Logicrays\StockNotification\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Logicrays\StockNotification\Model\StockNotificationFactory;
use Logicrays\StockNotification\Model\ProductnotificationFactory;
use Logicrays\StockNotification\Helper\Email;
use Logicrays\StockNotification\Helper\Data;
use Logicrays\StockNotification\Helper\Apicall;
use Magento\Email\Model\Template\Filter;

class StockNotificationForm implements ResolverInterface
{
    /**
     * @var StockNotificationFactory
     */
    private $stockNotificationFactory;

    /**
     * @var ProductnotificationFactory
     */
    private $productNotificationFactory;

    /**
     * @var Email
     */
    protected $emailHelper;

    /**
     * @var Data
     */
    protected $dataHelper;

    /**
     * @var Apicall
     */
    protected $apiHelper;

    /**
     * @var Filter
     */
    protected $emailfilter;

    /**
     * @param StockNotificationFactory $stockNotificationFactory
     * @param ProductnotificationFactory $productNotificationFactory
     * @param Email $emailHelper
     * @param Data $dataHelper
     * @param Apicall $apiHelper
     * @param Filter $emailfilter
     */
    public function __construct(
        StockNotificationFactory $stockNotificationFactory,
        ProductnotificationFactory $productNotificationFactory,
        Email $emailHelper,
        Data $dataHelper,
        Apicall $apiHelper,
        Filter $emailfilter
    ) {
        $this->stockNotificationFactory = $stockNotificationFactory;
        $this->productNotification = $productNotificationFactory;
        $this->emailHelper = $emailHelper;
        $this->dataHelper = $dataHelper;
        $this->apiHelper = $apiHelper;
        $this->emailfilter = $emailfilter;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (empty($args['input']) || !is_array($args['input'])) {
            throw new GraphQlInputException(__('"input" value should be specified'));
        }
        $this->validateData($args['input']);
        $result = $this->saveData($args['input']);
        return $result;
    }

    /**
     * Validate Form Field.
     *
     * @param array $data
     * @return void
     */
    public function validateData($data)
    {
        $websiteName = $data['websitename'];
        $productId = $data['product_id'];
        $productName = $data['product_name'];
        $productSku = $data['product_sku'];
        $subscriberName = $data['subscriber_name'];
        $subscriberEmail = $data['subscriber_email'];
        $subscriberMobile = $data['subscriber_mobile'];

        if (empty($websiteName)) {
            throw new GraphQlInputException(__('Website Name should be specified.'));
        }
        if (empty($productId)) {
            throw new GraphQlInputException(__('Product Id should be specified.'));
        }
        if (empty($productName)) {
            throw new GraphQlInputException(__('Product Name should be specified.'));
        }
        if (empty($productSku)) {
            throw new GraphQlInputException(__('Product Sku should be specified.'));
        }
        if (!isset($subscriberName)) {
            throw new GraphQlInputException(__('Subscriber Name should be specified.'));
        }
        if (empty($subscriberEmail)) {
            throw new GraphQlInputException(__('Subscriber Email should be specified.'));
        }
        if (empty($subscriberMobile)) {
            throw new GraphQlInputException(__('Subscriber Mobile should be specified.'));
        }
    }

    /**
     * SaveData function
     *
     * @param array $data
     * @return array
     */
    public function saveData($data)
    {
        try {
            $output = [];
            $stockNotificationModel = $this->stockNotificationFactory->create();
            $stockNotificationModel->setData($data);
            $subscriberEmail = $stockNotificationModel->getData('subscriber_email');
            $productId = $stockNotificationModel->getData('product_id');
            $collection = $stockNotificationModel->getCollection();

            $sucsess = "Thanks for your interest, we will notify you when the Product becomes available";
            if ($this->dataHelper->getNotificationSucsess()) {
                $sucsess = $this->dataHelper->getNotificationSucsess();
            }
            $pending = $collection->addFieldToFilter('notification_status', 'Pending');
            $email = [];
            foreach ($pending as $key => $pendingdata) {
                    $email[] = $pendingdata->getSubscriberEmail();
            }
            $ProductNotification = $this->productNotification->create();
            if (in_array($data['subscriber_email'], $email)) {
                $match = $collection->addFieldToFilter('subscriber_email', $data['subscriber_email']);
                foreach ($match as $key => $matchvalue) {
                      $product_id = $matchvalue->getProductId();
                }
                if ($product_id == $data['product_id']) {
                    $output = [
                        'success' => 'false',
                        'message' => 'You Have Already Subscribe this Product.'
                        ];
                    return $output;
                } else {
                    $stockNotificationModel->save();

                    $ProductNotificationCollection = $ProductNotification->getCollection();
                    $allproductcollection = $ProductNotificationCollection->
                    addFieldToFilter('product_id', $data['product_id']);
  
                    foreach ($allproductcollection as $key => $collectionvalue) {
                            $pid = $collectionvalue->getProductId();
                    }
  
                    if (isset($pid)) {
                        if ($pid == $data['product_id']) {
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
                        $ProductNotification->setProductId($data['product_id'])
                          ->setProductName($data['product_name'])
                          ->setProductSku($data['product_sku'])
                          ->setTotalNotification($totalnotification)
                          ->setPendingNotification($pendingnotification)
                          ->save();
                    }
  
                    if ($this->dataHelper->getStockNotificationType() == 'sms' ||
                    $this->apiHelper->getOutofstockAdminEnable() ||
                    $this->dataHelper->getStockNotificationType() == 'smsemail') {
                                $adminmobile = $this->apiHelper->getAdminNumber();
                                $this->emailfilter->setVariables([
                                        'product' => $data['product_name'],
                                        'customername' => $data['subscriber_name']
                                    ]);
                                $message = $this->apiHelper->isOutofstockNotificationForAdmin();
                                $finalmessage = $this->emailfilter->filter($message);
                                $this->apiHelper->callApiAdmin($adminmobile, $finalmessage);
                    }
                    if ($this->dataHelper->getStockNotificationType() == 'email' ||
                        $this->dataHelper->getStockNotificationType() == 'smsemail') {
                            $this->emailHelper->sendSubscribedEmail($data['subscriber_email'], $data['product_id']);
                    }
                }
            } else {
                $stockNotificationModel->save();
    
                $ProductNotificationCollection = $ProductNotification->getCollection();
                $allproductcollection = $ProductNotificationCollection->
                addFieldToFilter('product_id', $data['product_id']);
    
                foreach ($allproductcollection as $key => $collectionvalue) {
                      $pid = $collectionvalue->getProductId();
                }
    
                $totalnotification = 1;
                $pendingnotification = 1;
    
                if (isset($pid)) {
                    if ($pid == $data['product_id']) {
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
                        $ProductNotification->setProductId($data['product_id'])
                            ->setProductName($data['product_name'])
                            ->setProductSku($data['product_sku'])
                            ->setTotalNotification($totalnotification)
                            ->setPendingNotification($pendingnotification)
                            ->save();
                }
    
                if ($this->dataHelper->getStockNotificationType() == 'sms' ||
                $this->apiHelper->getOutofstockAdminEnable() ||
                $this->dataHelper->getStockNotificationType() == 'smsemail') {
                      $adminmobile = $this->apiHelper->getAdminNumber();
                      $this->emailfilter->setVariables([
                              'product' => $data['product_name'],
                              'customername' => $data['subscriber_name']
                          ]);
                      $message = $this->apiHelper->isOutofstockNotificationForAdmin();
                      $finalmessage = $this->emailfilter->filter($message);
                      $this->apiHelper->callApiAdmin($adminmobile, $finalmessage);
                }
    
                if ($this->dataHelper->getStockNotificationType() == 'email' ||
                $this->dataHelper->getStockNotificationType() == 'smsemail') {
                      $this->emailHelper->sendSubscribedEmail($data['subscriber_email'], $data['product_id']);
                }
            }
            $output = [
                'success' => 'true',
                'message' => $sucsess
                ];
        } catch (InputException $exception) {
            throw new InputException(__($exception->getMessage()));
        }
        return $output;
    }
}
