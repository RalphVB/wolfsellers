<?php

namespace Wolfsellers\Referral\Controller\Manage;

use Magento\Customer\Controller\AccountInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Wolfsellers\Referral\Model\ReferralFactory;
use Wolfsellers\Referral\Model\ResourceModel\Referral as ReferralResource;
use Magento\Framework\Message\ManagerInterface;
use Psr\Log\LoggerInterface;

class Save implements AccountInterface, HttpPostActionInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var ReferralFactory
     */
    protected $referralFactory;

    /**
     * @var ReferralResource
     */
    protected $referralResource;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Dependency Initilization
     *
     * @param RequestInterface $request
     * @param RedirectFactory $resultRedirectFactory
     * @param CustomerSession $customerSession
     * @param ReferralFactory $referralFactory
     * @param ReferralResource $referralResource
     * @param ManagerInterface $messageManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        RequestInterface $request,
        RedirectFactory $resultRedirectFactory,
        CustomerSession $customerSession,
        ReferralFactory $referralFactory,
        ReferralResource $referralResource,
        ManagerInterface $messageManager,
        LoggerInterface $logger
    ) {
        $this->request = $request;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->referralFactory = $referralFactory;
        $this->referralResource = $referralResource;
        $this->customerSession = $customerSession;
        $this->messageManager = $messageManager;
        $this->logger = $logger;
    }

    /**
     * Saves Referral and redirects to Index.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $redirect = $this->resultRedirectFactory->create();

        try {
            $data = $this->request->getParams();

            $referral = $this->referralFactory->create()->setData($data);
            
            $referral->unsetData('entity_id');
            $referral->unsetData('status');
            $referral->unsetData('customer_id');

            $validate = $referral->validate();

            if ($validate === true) {
                if( !empty($data['entity_id'])) {
                    $referral->load($data['entity_id']);
                } else {
                    $referral->setStatus(false);
                    $referral->setCustomerId($this->customerSession->getCustomer()->getId());
                }
    
                $referral->setFirstName($data['first_name']);
                $referral->setLastName($data['last_name']);
                $referral->setEmail($data['email']);
                $referral->setPhone($data['phone']);
    
                $this->referralResource->save($referral);
            
                // TODO: SEND EMAIL FOR REFERRAL.
        
                $this->messageManager->addSuccessMessage(__('Referral Saved Successfully.'));
            } else {
                if (is_array($validate)) {
                    foreach ($validate as $errorMessage) {
                        $this->messageManager->addErrorMessage($errorMessage);
                    }
                } else {
                    $this->messageManager->addErrorMessage(__("We can't save your referral right now."));
                }

                return $redirect->setRefererUrl();
            }
            
        } catch (\Exception $ex) {
            $this->messageManager->addErrorMessage(__('Something went wrong. Please try again.'));
            $this->logger->debug($ex->getMessage());
        }
        
        return $redirect->setPath('referral/manage');
    }
}