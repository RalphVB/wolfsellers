<?php
namespace Wolfsellers\Referral\Model\ResourceModel;

class Referral extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Dependency Initilization
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init("wolfsellers_referral", "entity_id");
    }
}