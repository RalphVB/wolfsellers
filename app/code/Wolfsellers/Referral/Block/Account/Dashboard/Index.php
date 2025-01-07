<?php

namespace Wolfsellers\Referral\Block\Account\Dashboard;

use Wolfsellers\Referral\Model\ResourceModel\Referral\CollectionFactory as ReferralCollection;
use Magento\Framework\Exception\NoSuchEntityException;

class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Customer\Helper\Session\CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @var ReferralCollection
     */
    protected $referalCollection;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer
     * @param ReferralCollection $referalCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        ReferralCollection $referalCollection,
        array $data = []
    ) {
        $this->currentCustomer = $currentCustomer;
        $this->referalCollection = $referalCollection;
        parent::__construct($context, $data);
    }

    /**
     * Get the logged in customer
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface|null
     */
    public function getCustomer()
    {
        try {
            return $this->currentCustomer->getCustomer();
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * Get Referrals List
     * 
     * @return ReferralCollection
     */
    public function getReferrals() {
        return $this->referalCollection->create();
    }

    /**
     * Get message for no referrals.
     *
     * @return \Magento\Framework\Phrase
     */
    public function getEmptyReferralssMessage()
    {
        return __('You have no referrals yet.');
    }

    /**
     * Get Form URL
     * 
     * @return string
     */
    public function getFormUrl() {
        return $this->getUrl('referral/manage/form', ['_secure' => true]);
    }
}
