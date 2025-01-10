<?php
namespace Wolfsellers\Referral\Model;

use Magento\Framework\Validator\NotEmpty;
use Magento\Framework\Validator\EmailAddress;
use Magento\Framework\Validator\ValidateException;
use Magento\Framework\Validator\ValidatorChain;
use Laminas\I18n\Validator\IsInt;
use Laminas\I18n\Validator\Alpha;

class Referral extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    /**
     * No route page id.
     */
    const NOROUTE_ENTITY_ID = 'no-route';
    
    /**
     * Entity Id
     */
    const ENTITY_ID = 'entity_id';
    
    /**
     * Cache tag.
     */
    const CACHE_TAG = 'wolfsellers_referral_referral';
    
    /**
     * @var string
     */
    protected $_cacheTag = 'wolfsellers_referral_referral';
    
    /**
     * Dependency Initilization
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(\Wolfsellers\Referral\Model\ResourceModel\Referral::class);
    }

    /**
     * Get Identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG.'_'.$this->getId()];
    }

    /**
     * Get Id
     *
     * @return int|null
     */
    public function getId()
    {
        return parent::getData(self::ENTITY_ID);
    }

    /**
     * Set Id
     *
     * @param int $id
     * @return \Wolfsellers\Referral\Model\Referral
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * Validate Referral Fields
     *
     * @return bool|string[]
     * @throws ValidateException
     */
    public function validate()
    {
        $errors = [];

        if($this->load($this->getId())) {
            $errors[] = __("The Referral you are trying to register already exists.");
        }

        if (!ValidatorChain::is($this->getFirstName(), NotEmpty::class)) {
            $errors[] = __('Please enter a first name.');
        } else {
            if (!ValidatorChain::is($this->getFirstName(), Alpha::class)) {
                $errors[] = __('Please enter only characters as first name.');
            }
        }

        if (!ValidatorChain::is($this->getLastName(), NotEmpty::class)) {
            $errors[] = __('Please enter a last name.');
        } else {
            if (!ValidatorChain::is($this->getLastName(), Alpha::class)) {
                $errors[] = __('Please enter only characters as last name.');
            }
        }
        
        if (!ValidatorChain::is($this->getEmail(), NotEmpty::class)) {
            $errors[] = __('Please enter an email.');
        } else {
            if (!ValidatorChain::is($this->getEmail(), EmailAddress::class)) {
                $errors[] = __('Please enter a valid email address.');
            }
        }

        if (!ValidatorChain::is($this->getPhone(), NotEmpty::class)) {
            $errors[] = __('Please enter an email.');
        } else {
            if (!ValidatorChain::is($this->getPhone(), IsInt::class)) {
                $errors[] = __('Please enter a phone number.');
            }
        }

        if (empty($errors)) {
            return true;
        }
        
        return $errors;
    }
}