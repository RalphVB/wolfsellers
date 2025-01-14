<?php

namespace Wolfsellers\Referral\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Area;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class SendEmail implements ObserverInterface
{
    const MAIL_TEMPLATE = 'wolfsellers_referral_register_email_template';

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManagerInterface;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfigInterface;

    /**
     * Dependency Injection
     *
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManagerInterface
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManagerInterface,
        ScopeConfigInterface $scopeConfigInterface
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->storeManagerInterface = $storeManagerInterface;
        $this->scopeConfigInterface = $scopeConfigInterface;
    }

    public function execute(Observer $observer)
    {
        $storeId = $this->storeManagerInterface->getStore()->getId();
        $referral = $observer->getData('referral');
        $customer = $observer->getData('customer');

        $vars = [
            "referral_name" => $referral->getFirstName() . " " . $referral->getLastName(),
            "customer_name" => $customer->getName(),
            "link" => null
        ];

        $this->transportBuilder->setTemplateIdentifier(
            self::MAIL_TEMPLATE
        )->setTemplateOptions(
            [
                'area'  => Area::AREA_FRONTEND,
                'store' => $storeId,
            ]
        )->setTemplateVars($vars)->setFromByScope(
            [
                "email" => $this->scopeConfigInterface->getValue('trans_email/ident_support/email', ScopeInterface::SCOPE_STORE),
                "name" => $this->scopeConfigInterface->getValue('trans_email/ident_support/name', ScopeInterface::SCOPE_STORE)
            ]
        )->addTo(
            $referral->getEmail(),
            $vars["referral_name"]
        )->getTransport()->sendMessage();

        return $this;
    }
}
