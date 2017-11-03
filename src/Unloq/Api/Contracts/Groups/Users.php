<?php
namespace Unloq\Api\Contracts\Groups;

use Unloq\Api\Common\UnloqModel;

/**
 * Class Users
 *
 * @package Unloq\Api\Contracts
 *
 * @property string user_id                      - Optional
 */
class Users extends UnloqModel
{
    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param string $userId
     *
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

}