<?php

namespace Wolfsellers\Referral\Ui\Component\DataProvider;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider as UiDataProvider;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Exception\LocalizedException;

use Wolfsellers\Referral\Model\ResourceModel\Referral\CollectionFactory as ReferralCollectionFactory;

class Referral extends UiDataProvider
{
    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @var ReportingInterface
     */
    protected $reporting;

    /**
     * @var ReferralCollectionFactory
     */
    protected $referralCollection;


    protected $meta = [];
    protected $data = [];

    /**
     * @param array<int, array<int, string>> $meta
     * @param array<int, array<int, string>> $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        ReferralCollectionFactory $referralCollection,
        array $meta = [],
        array $data = []
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->referralCollection = $referralCollection;
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
    }

    public function getData(): array
    {
        try {
            $searchCriteria = $this->searchCriteriaBuilder->create();
            $collection = $this->referralCollection->create()->load();
            return [
                'totalRecords' => count($collection),
                'items' => $collection->getData(),
            ];
        } catch (LocalizedException $e) {
            return [
                'items' => [],
                'error' => 'Server Error: Please contact the administrator if it persists !!',
            ];
        }
    }
}