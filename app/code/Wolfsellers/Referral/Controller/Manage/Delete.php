<?php
namespace Wolfsellers\Referral\Controller\Manage;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Customer\Controller\AccountInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Wolfsellers\Referral\Model\ReferralFactory;
use Wolfsellers\Referral\Model\ResourceModel\Referral as ReferralResource;
use Magento\Framework\Message\ManagerInterface;
use Psr\Log\LoggerInterface;

class Delete implements AccountInterface, HttpGetActionInterface
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
     * @param ReferralFactory $referralFactory
     * @param ReferralResource $referralResource
     * @param ManagerInterface $messageManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        RequestInterface $request,
        RedirectFactory $resultRedirectFactory,
        ReferralFactory $referralFactory,
        ReferralResource $referralResource,
        ManagerInterface $messageManager,
        LoggerInterface $logger
    ) {
        $this->request = $request;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->referralFactory = $referralFactory;
        $this->referralResource = $referralResource;
        $this->messageManager = $messageManager;
        $this->logger = $logger;
    }

    /**
     * Deletes Referral and redirect to Index
     *
     * @return Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        try {
            $referralID = $this->request->getParam('id');

            $referral = $this->referralFactory->create()->load($referralID);
            
            $this->referralResource->delete($referral);

            $this->messageManager->addSuccessMessage(__('Referral Deleted Successfully.'));
        } catch (\Exception $ex) {
            $this->messageManager->addErrorMessage(__('Something went wrong. Please try again.'));
            $this->logger->debug($ex->getMessage());
        }

        return $this->resultRedirectFactory->create()->setPath('referral/manage');
    }
}
