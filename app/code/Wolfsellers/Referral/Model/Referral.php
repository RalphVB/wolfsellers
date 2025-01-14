<?php
namespace Wolfsellers\Referral\Model;

class Referral extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
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
}
