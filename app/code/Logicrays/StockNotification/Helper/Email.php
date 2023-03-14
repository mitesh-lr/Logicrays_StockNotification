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

namespace Logicrays\StockNotification\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Store\Model\ScopeInterface;

class Email extends AbstractHelper
{
    public const OUTOFSTOCK_NOTIFICATION_ADMIN_EMAIL = 'stocknotification/general/admin_email_notification';
    public const EMAIL_TEMPLATE = 'stocknotification/general/email_template';
    public const EMAIL_TEMPLATE_ADMIN = 'stocknotification/general/email_template_admin';
    public const EMAIL_TEMPLATE_INSTOCK = 'stocknotification/general/email_template_instock';
    protected const XML_PATH_GENERAL_NAME = 'trans_email/ident_general/name';
    protected const XML_PATH_GENERAL_EMAIL = 'trans_email/ident_general/email';
    protected const XML_PATH_SALES_NAME = 'trans_email/ident_sales/name';
    protected const XML_PATH_SALES_EMAIL = 'trans_email/ident_sales/email';
    protected const XML_PATH_CUSTOMER_NAME = 'trans_email/ident_support/name';
    protected const XML_PATH_CUSTOMER_EMAIL = 'trans_email/ident_support/email';
    protected const XML_PATH_CUSTOM1_NAME = 'trans_email/ident_custom1/name';
    protected const XML_PATH_CUSTOM1_EMAIL = 'trans_email/ident_custom1/email';
    protected const XML_PATH_CUSTOM2_NAME = 'trans_email/ident_custom2/name';
    protected const XML_PATH_CUSTOM2_EMAIL = 'trans_email/ident_custom2/email';
    public const XML_PATH_EMAIL_SENDER = 'stocknotification/general/sender_email_identity';

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param StateInterface $inlineTranslation
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        ProductRepositoryInterface $productRepository
    ) {
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->productRepository = $productRepository;
        parent::__construct($context);
    }

    /**
     * Get store name
     *
     * @return string
     */
    public function getStorename()
    {
        return $this->scopeConfig->getValue(
            self::TRANS_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get notification admin email
     *
     * @return string
     */
    public function getNotificationAdminEmail()
    {
        return $this->scopeConfig->
        getValue(self::OUTOFSTOCK_NOTIFICATION_ADMIN_EMAIL, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get value of Email Sender from Admin side.
     *
     * @return boolean
     */
    public function emailSender()
    {
        return $this->scopeConfig
        ->getValue(
            self::XML_PATH_EMAIL_SENDER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get from array
     *
     * @return array
     */
    public function getFromArray()
    {
        return ['email' => $this->getStoreEmail(), 'name' => $this->getStorename()];
    }

    /**
     * Get template options array
     *
     * @return array
     */
    public function getTemplateOptionsArray()
    {
        return  ['area' => \Magento\Framework\App\Area::AREA_FRONTEND,
        'store' => $this->storeManager->getStore()->getId()];
    }

    /**
     * Send subscribed email
     *
     * @param string $email
     * @param int $productId
     * @return void
     */
    public function sendSubscribedEmail($email, $productId)
    {
        $emailSender = $this->emailSender();
        switch ($emailSender) {
            case "general":
                $xmlPathName  = self::XML_PATH_GENERAL_NAME;
                $xmlPathEmail = self::XML_PATH_GENERAL_EMAIL;
                break;
            case "sales":
                $xmlPathName  = self::XML_PATH_SALES_NAME;
                $xmlPathEmail = self::XML_PATH_SALES_EMAIL;
                break;
            case "support":
                $xmlPathName  = self::XML_PATH_CUSTOMER_NAME;
                $xmlPathEmail = self::XML_PATH_CUSTOMER_EMAIL;
                break;
            case "custom1":
                $xmlPathName  = self::XML_PATH_CUSTOM1_NAME;
                $xmlPathEmail = self::XML_PATH_CUSTOM1_EMAIL;
                break;
            case "custom2":
                $xmlPathName  = self::XML_PATH_CUSTOM2_NAME;
                $xmlPathEmail = self::XML_PATH_CUSTOM2_EMAIL;
                break;
        }
        $product = $this->productRepository->getById($productId);
        $templateVars = [
            'store' => $this->storeManager->getStore(),
            'customer_name' => $email,
            'product'   =>$product->getName(),
            'producturl' =>$product->getUrl()
        ];
        $this->inlineTranslation->suspend();
        $storeId = $this->storeManager->getStore()->getStoreId();
        $storeScope = ScopeInterface::SCOPE_STORE;
        $emailTemplate = $this->scopeConfig->getValue(
            self::EMAIL_TEMPLATE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        // $emailSender = [
        //         'name' => $xmlPathName,
        //         'email' => $xmlPathEmail
        //     ];
        $emailReceiverName = "Customer";
        $emailReceiverEmail = $email;

        $transport = $this->transportBuilder
            ->setTemplateIdentifier($emailTemplate)
            ->setTemplateOptions(
                [
                    'area' => 'frontend',
                    'store' => $storeId,
                ]
            )
            ->setTemplateVars($templateVars)
            ->setFrom(
                [
                    'email' => $this->scopeConfig
                    ->getValue(
                        $xmlPathEmail,
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    ),
                    'name' => $this->scopeConfig
                    ->getValue(
                        $xmlPathName,
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    )
                ]
            )
            ->addTo($emailReceiverEmail, $emailReceiverName)
            ->getTransport();

        $transport->sendMessage();
        $this->inlineTranslation->resume();

        /*Admin email send out of stock notification inqury */
        $adminemailReceiverName = "Admin";
        $adminemailReceiverEmail = $this->getNotificationAdminEmail();
        $admintemplateVars = [
                'store' => $this->storeManager->getStore(),
                'customer_name' => $email,
                'product'   =>$product->getName(),
                'producturl'   =>$product->getUrl()
        ];

        $AdminemailTemplate = $this->scopeConfig->getValue(
            self::EMAIL_TEMPLATE_ADMIN,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $transport = $this->transportBuilder
            ->setTemplateIdentifier($AdminemailTemplate)
            ->setTemplateOptions(
                [
                    'area' => 'frontend',
                    'store' => $storeId,
                ]
            )
            ->setTemplateVars($admintemplateVars)
            ->setFrom(
                [
                    'email' => $this->scopeConfig
                    ->getValue(
                        $xmlPathEmail,
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    ),
                    'name' => $this->scopeConfig
                    ->getValue(
                        $xmlPathName,
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    )
                ]
            )
            ->addTo($adminemailReceiverEmail, $adminemailReceiverName)
            ->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }

    /**
     * Send back in stock email
     *
     * @param string $email
     * @param int $productId
     * @return void
     */
    public function sendBackInStockEmail($email, $productId)
    {
        $emailSender = $this->emailSender();
        switch ($emailSender) {
            case "general":
                $xmlPathName  = self::XML_PATH_GENERAL_NAME;
                $xmlPathEmail = self::XML_PATH_GENERAL_EMAIL;
                break;
            case "sales":
                $xmlPathName  = self::XML_PATH_SALES_NAME;
                $xmlPathEmail = self::XML_PATH_SALES_EMAIL;
                break;
            case "support":
                $xmlPathName  = self::XML_PATH_CUSTOMER_NAME;
                $xmlPathEmail = self::XML_PATH_CUSTOMER_EMAIL;
                break;
            case "custom1":
                $xmlPathName  = self::XML_PATH_CUSTOM1_NAME;
                $xmlPathEmail = self::XML_PATH_CUSTOM1_EMAIL;
                break;
            case "custom2":
                $xmlPathName  = self::XML_PATH_CUSTOM2_NAME;
                $xmlPathEmail = self::XML_PATH_CUSTOM2_EMAIL;
                break;
        }
        $product = $this->productRepository->getById($productId);
        $templateVars = [
            'store' => $this->storeManager->getStore(),
            'customer_name' => $email,
            'message'   => 'Thanks for your registering your interest our product is back in stock',
            'product'   =>$product->getName(),
            'producturl'   =>$product->getUrl()
        ];
        $this->inlineTranslation->suspend();
        $storeId = $this->storeManager->getStore()->getStoreId();
        // $emailSender = [
        //           'name' => $xmlPathName,
        //           'email' => $xmlPathEmail
        //       ];
        $emailReceiverName = "Customer";
        $emailReceiverEmail = $email;
        $emailTemplateinstock = $this->scopeConfig->getValue(
            self::EMAIL_TEMPLATE_INSTOCK,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        $transport = $this->transportBuilder
            ->setTemplateIdentifier($emailTemplateinstock)
            ->setTemplateOptions(
                [
                    'area' => 'frontend',
                    'store' => $storeId,
                ]
            )
            ->setTemplateVars($templateVars)
            ->setFrom(
                [
                    'email' => $this->scopeConfig
                    ->getValue(
                        $xmlPathEmail,
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    ),
                    'name' => $this->scopeConfig
                    ->getValue(
                        $xmlPathName,
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    )
                ]
            )
            ->addTo($emailReceiverEmail, $emailReceiverName)
            ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();
    }
}
