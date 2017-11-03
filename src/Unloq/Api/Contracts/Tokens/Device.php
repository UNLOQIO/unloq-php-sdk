<?php
namespace Unloq\Api\Contracts\Tokens;

use Unloq\Api\Common\UnloqModel;

/**
 * Class Device
 *
 * @package Unloq\Api\Contracts
 *
 * @property string token                            - Required
 */
class Device extends UnloqModel
{
    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }
}