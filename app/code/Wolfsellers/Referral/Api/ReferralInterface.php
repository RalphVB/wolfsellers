<?php

namespace Wolfsellers\Referral\Api;

interface ReferralInterface
{
    /**
     * GET Referral Collection
     * @return string
     */
    public function getReferrals();

    /**
     * POST Referral by value
     * @param string[] $values
     * @return string
     */
    public function getBy($values);

    /**
     * POST Referral
     * @param string[] $data
     * @return string|bool
     */
    public function create($data);

    /**
     * DELETE Referral
     * @param int $id
     * @return string|bool
     */
    public function delete(int $id);
}
