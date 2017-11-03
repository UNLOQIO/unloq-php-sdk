<?php
namespace Unloq\Api\Contracts\Approval;

use Unloq\Api\Common\UnloqModel;

/**
 * Class Authorise
 *
 * @package Unloq\Api\Contracts
 *
 * @property string code                                - Required
 *
 * @property string unloq_id                            - Optional
 * @property string email                               - Required
 * @property integer reference                          - Required
 * @property string ip                                  - Optional
 * @property boolean generate_token                     - Optional
 */
class Authorize extends UnloqModel {
    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getUnloqId()
    {
        return $this->unloq_id;
    }

    /**
     * @param string $unloqId
     *
     * @return $this
     */
    public function setUnloqId($unloqId)
    {
        $this->unloq_id = $unloqId;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return int
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param int $reference
     *
     * @return $this
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

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
    public function isGenerateToken()
    {
        return $this->generate_token;
    }

    /**
     * @param bool $generateToken
     *
     * @return $this
     */
    public function setGenerateToken($generateToken)
    {
        $this->generate_token = $generateToken;

        return $this;
    }

}