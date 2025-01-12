<?php

namespace Wolfsellers\Referral\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

use Magento\Customer\Api\CustomerRepositoryInterface;

use Psr\Log\LoggerInterface;

class ReferredEmail extends Column
{

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRespositoryInterface;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     *
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param CustomerRepositoryInterface $customerRespositoryInterface
     * @param array              $components
     * @param array              $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CustomerRepositoryInterface $customerRespositoryInterface,
        LoggerInterface $logger,
        array $components = [],
        array $data = []
    ) {
        $this->customerRespositoryInterface = $customerRespositoryInterface;
        $this->logger = $logger;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        // $this->logger->debug("*** Referred Email COLUMN ***");
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item)
            {
                $customer = $this->customerRespositoryInterface->getById($item['customer_id']);
                $email = $customer->getEmail();

                if($email) {
                    $item[$this->getData('name')] = $email;
                }

                // $this->logger->debug(print_r($item, true));
            }
        }
        return $dataSource;
    }
}
?>
