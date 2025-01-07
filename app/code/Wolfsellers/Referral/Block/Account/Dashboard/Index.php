<?php

namespace Wolfsellers\Referral\Block\Account\Dashboard;

use Wolfsellers\Referral\Model\ResourceModel\Referral\CollectionFactory as ReferralCollection;
use Magento\Framework\Exception\NoSuchEntityException;

class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * @var ReferralCollection
     */
    protected $referalCollection;

    /**
     * Dependency Injection
     * 
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param ReferralCollection $referalCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        ReferralCollection $referalCollection,
        array $data = []
    ) {
        $this->referalCollection = $referalCollection;
        parent::__construct($context, $data);
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

    /**
     * Get Edit URL
     * 
     * @param int $id
     * @return string
     */
    public function getEditUrl(int $id) {
        return $this->getUrl('referral/manage/form', ['id'=> $this->_escaper->escapeHtml($id)]);
    }
}
