<?php
namespace Wolfsellers\Referral\Model;

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
}