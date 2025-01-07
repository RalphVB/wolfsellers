<?php

namespace Wolfsellers\Referral\Block\Account\Dashboard;

use Magento\Backend\Block\Template\Context;

class Form extends \Magento\Framework\View\Element\Template
{

    /**
     * Dependency Injection
     * 
     * @param Context $context
     * @param array $data
     */
    public function __construct(Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }
    
    /**
     * Get Save URL
     */
    public function getFormAction()
    {
        return $this->getUrl('referral/manage/save', ['_secure' => true]);
    }

    /**
     * Get Referral Index URL
     */
    public function getBackUrl() {
        return $this->getUrl('referral/manage');
    }
}
