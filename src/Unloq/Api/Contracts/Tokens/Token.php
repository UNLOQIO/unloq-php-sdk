<?php
namespace Unloq\Api\Contracts\Tokens;

use Unloq\Api\Common\UnloqModel;

/**
 * Class Token
 *
 * @package Unloq\Api\Contracts
 *
 * @property string token                            - Required
 * @property string sid                              - Optional
 * @property integer duration                        - Optional
 * @property string ip                               - Optional
 * @property boolean temporary_keys                  - Optional
 */
class Token extends UnloqModel
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

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     *
     * @return $this
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTemporaryKeys()
    {
        return $this->temporary_keys;
    }

    /**
     * @param bool $temporaryKeys
     *
     * @return $this
     */
    public function setTemporaryKeys($temporaryKeys)
    {
        $this->temporary_keys = $temporaryKeys;

        return $this;
    }
}