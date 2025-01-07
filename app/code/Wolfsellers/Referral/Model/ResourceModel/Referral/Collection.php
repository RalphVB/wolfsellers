<?php
namespace Wolfsellers\Referral\Model\ResourceModel\Referral;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';
    
    /**
     * Dependency Initilization
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(
            \Wolfsellers\Referral\Model\Referral::class,
            \Wolfsellers\Referral\Model\ResourceModel\Referral::class
        );
        $this->_map['fields']['entity_id'] = 'main_table.entity_id';
    }
}