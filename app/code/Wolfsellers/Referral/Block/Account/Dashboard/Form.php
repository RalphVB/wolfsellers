<?php

namespace Wolfsellers\Referral\Block\Account\Dashboard;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\App\RequestInterface;
use Wolfsellers\Referral\Model\ReferralFactory;

class Form extends \Magento\Framework\View\Element\Template
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ReferralFactory
     */
    protected $referralFactory;

    /**
     * Dependency Injection
     * 
     * @param Context $context
     * @param RequestInterface $request
     * @param ReferralFactory $referralFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        RequestInterface $request,
        ReferralFactory $referralFactory,
        array $data = []
    )
    {
        $this->request = $request;
        $this->referralFactory = $referralFactory;
        parent::__construct($context, $data);
    }
    
    /**
     * Get Save URL
     * 
     * @return string
     */
    public function getFormAction()
    {
        return $this->getUrl('referral/manage/save', ['_secure' => true]);
    }

    /**
     * Get Referral Index URL
     * 
     * @return string
     */
    public function getBackUrl() {
        return $this->getUrl('referral/manage');
    }

    /**
     * Get Referral or Create It.
     * 
     * @return \Wolfsellers\Referral\Model\Referral
     */
    public function getOrCreateReferral()
    {
        $referralId =$this->request->getParam('id'); 
        $referral = $this->referralFactory->create();

        if($referralId !== null) {
            return $referral->load($referralId);
        }

        return $referral;
    }
}
