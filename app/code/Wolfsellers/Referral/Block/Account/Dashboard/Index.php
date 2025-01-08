<?php

namespace Wolfsellers\Referral\Block\Account\Dashboard;

use Magento\Customer\Model\Session as CustomerSession;
use Wolfsellers\Referral\Model\ResourceModel\Referral\CollectionFactory as ReferralCollection;

class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var ReferralCollection
     */
    protected $referalCollection;

    /**
     * @var \Wolfsellers\Referral\Model\ResourceModel\Referral\Collection
     */
    protected $referrals;

    /**
     * Dependency Injection
     * 
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param CustomerSession $customerSession
     * @param ReferralCollection $referalCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        CustomerSession $customerSession,
        ReferralCollection $referalCollection,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->referalCollection = $referalCollection;
        parent::__construct($context, $data);
    }

    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getReferrals()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'sales.order.history.pager'
            )->setCollection(
                $this->getReferrals()
            );
            $this->setChild('pager', $pager);
            $this->getReferrals()->load();
        }
        return $this;
    }

    /**
     * Get Pager child block output
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * Get Referrals List
     * 
     * @return ReferralCollection
     */
    public function getReferrals() {

        if(!$this->referrals) {
            $this->referrals = $this->referalCollection->create()->addFieldToFilter(
                'customer_id', ['eq' => $this->customerSession->getCustomer()->getId()])
            ->setOrder(
                'created_at',
                'desc'
            );
        }
        return $this->referrals;
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

    /**
     * Get Delete URL
     * 
     * @param int $id
     * @return string
     */
    public function getDeleteUrl(int $id) {
        return $this->getUrl('referral/manage/delete', ['id'=> $this->_escaper->escapeHtml($id)]);
    }
}
