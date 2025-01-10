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
            $referral = $this->referralFactory->create()->setData($data);

            $referral->unsetData('entity_id');
            $referral->unsetData('status');
            $referral->unsetData('customer_id');

            $validate = $referral->validate(); 

            if($validate === true ) {
                $referral->setFirstName($data['first_name']);
                $referral->setLastName($data['last_name']);
                $referral->setEmail($data['email']);
                $referral->setPhone($data['phone']);
                $referral->setStatus(false);
                $referral->setCustomerId(1);

                $this->referralResource->save($referral);

                return json_encode(['status' => true, 'message' => __("Referral added successfully!")]);

            } else {
                if (is_array($validate)) {
                    return json_encode(['status' => false, 'message' => $validate]);
                } else {
                    return json_encode(['status' => false, 'message' => __("We can't save your referral right now.")]);
                }
            }

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
