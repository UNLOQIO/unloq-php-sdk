<?php
namespace Unloq\Api\Contracts\Tokens;

use Unloq\Api\Common\UnloqModel;

/**
 * Class Session
 *
 * @package Unloq\Api\Contracts
 *
 * @property string token                            - Optional
 * @property string approval_id                      - Optional
 * @property string sid                              - Required
 * @property integer duration                        - Optional
 */
class Session extends UnloqModel
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

    /**
     * @return string
     */
    public function getSid()
    {
        return $this->sid;
    }

    /**
     * @param string $sid
     *
     * @return $this
     */
    public function setSid($sid)
    {
        $this->sid = $sid;

        return $this;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     *
     * @return $this
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }
}