<?php

namespace Wolfsellers\Referral\Controller\Manage;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Customer\Controller\AccountInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\RequestInterface;

class Form implements AccountInterface, HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * Dependency Injection
     * 
     * @param PageFactory $resultPageFactory
     * @param RequestInterface $request
     */
    public function __construct(
        PageFactory $resultPageFactory,
        RequestInterface $request
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->request = $request;
    }

    /**
     * Default customer account page
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $title = __('New Referral');

        if($this->request->getParam('id') !== null){
            $title = __("Update Referral");
        }
        
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set($title);
        return $resultPage;
    }
}
