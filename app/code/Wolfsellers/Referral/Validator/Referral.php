<?php

namespace Wolfsellers\Referral\Validator;

use Magento\Framework\Validator\ValidatorChain;
use Magento\Framework\Validator\NotEmpty;
use Magento\Framework\Validator\EmailAddress;
use Laminas\I18n\Validator\Alpha;
use Laminas\I18n\Validator\IsInt;

use Wolfsellers\Referral\Model\Referral as ReferralModel;
use Wolfsellers\Referral\Model\ResourceModel\Referral\CollectionFactory as ReferralCollection;

use Psr\Log\LoggerInterface;

class Referral
{
    /**
     * Dependency Injection
     * 
     * @var ReferralCollection
     */
    private $referralCollection;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Dependency Injection
     * 
     * @param ReferralCollection $referralCollection
     * @param LoggerInterface $logger
     */
    public function __construct(
        ReferralCollection $referralCollection,
        LoggerInterface $logger
    ) {
        $this->referralCollection = $referralCollection;
        $this->logger = $logger;
    }

    /**
     * Validate Referral Fields
     *
     * @param ReferralModel $referral
     * @return bool|string[]
     */
    public function validate($referral)
    {
        $errors = [];

        try {
            if (!ValidatorChain::is($referral->getEmail(), NotEmpty::class)) {
                $errors[] = __('Please enter an email.');
            } else {
                if (!ValidatorChain::is($referral->getEmail(), EmailAddress::class)) {
                    $errors[] = __('Please enter a valid email address.');
                } else {
                    if(!self::validateEmail($referral->getEmail())) {
                        $errors[] = __('The Referral already exists.');
                        return $errors;
                    }
                }
            }
    
            if (!ValidatorChain::is($referral->getFirstName(), NotEmpty::class)) {
                $errors[] = __('Please enter a first name.');
            } else {
                if (!ValidatorChain::is($referral->getFirstName(), Alpha::class)) {
                    $errors[] = __('Please enter only characters as first name.');
                }
            }
    
            if (!ValidatorChain::is($referral->getLastName(), NotEmpty::class)) {
                $errors[] = __('Please enter a last name.');
            } else {
                if (!ValidatorChain::is($referral->getLastName(), Alpha::class)) {
                    $errors[] = __('Please enter only characters as last name.');
                }
            }
    
            if (!ValidatorChain::is($referral->getPhone(), NotEmpty::class)) {
                $errors[] = __('Please enter an phone number.');
            } else {
                if (!ValidatorChain::is($referral->getPhone(), IsInt::class)) {
                    $errors[] = __('Please enter a valid phone number.');
                }
            }
    
            if (empty($errors)) {
                return true;
            }
        } catch (\Exception $ex) {
            $errors[] = __("Something went wrong.");
            $this->logger->debug($ex->getMessage());
        }
        
        return $errors;
    }

    /**
     * @param string $email
     * @return bool
     */
    private function validateEmail($email) {
        $collection = $this->referralCollection->create()->addFieldToFilter(
            'email', ['eq', $email]
        )->load();

        return count($collection) === 0;
    }
}
