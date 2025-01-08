<?php
namespace Wolfsellers\Referral\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wolfsellers\Referral\Model\ReferralFactory;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollection;
use Wolfsellers\Referral\Model\ResourceModel\Referral as ReferralResource;
use Psr\Log\LoggerInterface;
use Faker\Factory as FakerFactory;

class DeployReferralData extends Command
{
    /**
     * @var FakerFactory
     */
    protected $faker;

    /**
     * @var ReferralFactory
     */
    protected $referralFactory;

    /**
     * @var ReferralResource
     */
    protected $referralResource;

    /**
     * @var CustomerCollection
     */
    protected $customerCollection;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Dependency Injection
     * 
     * @param FakerFactory $faker
     * @param ReferralFactory $referralFactory
     * @param ReferralResource $referralResource
     * @param CustomerCollection $customerCollection
     * @param LoggerInterface $logger
     */
    public function __construct(
        FakerFactory $faker,
        ReferralFactory $referralFactory,
        ReferralResource $referralResource,
        CustomerCollection $customerCollection,
        LoggerInterface $logger
    ) {
        $this->faker = $faker;
        $this->referralFactory = $referralFactory;
        $this->referralResource = $referralResource;
        $this->customerCollection = $customerCollection;
        $this->logger = $logger;
        parent::__construct();
    }

   protected function configure()
   {
       $this->setName('deploy:referral:data');
       $this->setDescription('Deploy Dummy Data for Referrals.');
       
       parent::configure();
   }

   protected function execute(InputInterface $input, OutputInterface $output)
   {
        try {
            $customers = $this->getCustomerCollection();
            $faker = $this->faker->create();

            foreach($customers as $customer) {
                for ($i = 0; $i < 10; $i++) {
                    $referral = $this->referralFactory->create();

                    $referral->setFirstName($faker->firstName());
                    $referral->setLastName($faker->lastName());
                    $referral->setEmail($faker->email());
                    $referral->setPhone($faker->phoneNumber());
                    $referral->setStatus(false);
                    $referral->setCustomerId($customer->getId());

                    $this->referralResource->save($referral);
                }
            }

            return \Magento\Framework\Console\Cli::RETURN_SUCCESS;
        } catch(\Exception $ex) {
            $this->logger->error($ex->getMessage());
            return \Magento\Framework\Console\Cli::RETURN_FAILURE;
        }   
   }

    /**
     * Get customer collection
     */
    private function getCustomerCollection()
    {
        return $this->customerCollection->create();
    }
}
