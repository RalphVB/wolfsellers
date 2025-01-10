<?php 

namespace Wolfsellers\Referral\Model\Api;

use Wolfsellers\Referral\Api\ReferralInterface;
use Wolfsellers\Referral\Model\ReferralFactory;
use Wolfsellers\Referral\Model\ResourceModel\Referral as ReferralResource;
use Wolfsellers\Referral\Model\ResourceModel\Referral\CollectionFactory as ReferralCollection;

use Psr\Log\LoggerInterface;

class Referral implements ReferralInterface
{
    /**
     * @var ReferralFactory
     */
    private $referralFactory;

    /**
     * @var ReferralResource
     */
    private $referralResource;

    /**
     * @var ReferralCollection
     */
    private $referralCollection;

    /**
     * @var LoggerInterface;
     */
    private $logger;

    /**
     * @param ReferralFactory $referralFactory
     * @param ReferralResource $referralResource
     * @param ReferralCollection $referralCollection
     */
    public function __construct(
        ReferralFactory $referralFactory,
        ReferralResource $referralResource,
        ReferralCollection $referralCollection,
        LoggerInterface $logger
    )
    {
        $this->referralFactory = $referralFactory;
        $this->referralResource = $referralResource;
        $this->referralCollection = $referralCollection;
        $this->logger = $logger;
    }

    /**
     * GET Referral Collection
     * @return string
     */
    public function getReferrals()
    {
        try {
            $data = $this->referralCollection->create()->getData();
            return $data;
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * POST Referral by value
     * @param string[] $values
     * @return string
     */
    public function getBy($values)
    {
        try {
            if(!empty($values)){
                $referrals = $this->referralCollection->create();

                foreach($values as $index => $value) {
                    $referrals->addFieldToFilter( $index, ['eq' => $value] );
                }

                return $referrals->getData();
            }

            return ['status' => false, 'message' => __("Values are empty.")];

        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * POST Referral by value
     * 
     * @inheritDoc
     */
    public function create($data)
    {
        try {
            $referral = $this->referralFactory->create();

            $referral->setFirstName($data['firstname']);
            $referral->setLastName($data['lastname']);
            $referral->setEmail($data['email']);
            $referral->setPhone($data['phone']);
            $referral->setStatus(false);
            $referral->setCustomerId(1);

            $this->referralResource->save($referral);

            return json_encode(['status' => true, 'message' => __("Referral added successfully!")]);

        } catch(\Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * DELETE Referral
     * @param int $id
     * @return string
     */
    public function delete($id)
    {
        try {

            $referral = $this->referralFactory->create()->load($id);

            if ($referral->getId()) {
                $referral->delete();
                return json_encode(['status' => true, 'message' => __("Referral deleted successfully!")]);
            } else {
                return json_encode(['status' => false, 'message' => __("Referral with ID $id not found!")]);
            }

        } catch (\Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
