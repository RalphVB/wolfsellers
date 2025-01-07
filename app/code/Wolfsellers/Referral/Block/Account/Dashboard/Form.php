<?php

namespace Wolfsellers\Referral\Block\Account\Dashboard;

use Magento\Backend\Block\Template\Context;

class Form extends \Magento\Framework\View\Element\Template
{
    public function __construct(Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }
    
    public function getFormAction()
    {
        return $this->getUrl('referral/manage/save', ['_secure' => true]);
    }
}
