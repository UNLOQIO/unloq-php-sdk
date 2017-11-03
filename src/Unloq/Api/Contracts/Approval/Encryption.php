<?php
namespace Unloq\Api\Contracts\Approval;

use Unloq\Api\Common\UnloqModel;

/**
 * Class Encryption
 *
 * @package Unloq\Api\Contracts
 *
 * @property string unloq_id                            - Optional
 * @property string email                               - Required
 * @property integer message                            - Required
 * @property boolean requester_id                       - Optional
 * @property boolean public_key                         - Optional
 * @property boolean generate_token                     - Optional
 * @property string ip                                  - Optional
 */
class Encryption extends UnloqModel {
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
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param int $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRequesterId()
    {
        return $this->requester_id;
    }

    /**
     * @param bool $requesterId
     *
     * @return $this
     */
    public function setRequesterId($requesterId)
    {
        $this->requester_id = $requesterId;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPublicKey()
    {
        return $this->public_key;
    }

    /**
     * @param bool $publicKey
     *
     * @return $this
     */
    public function setPublicKey($publicKey)
    {
        $this->public_key = $publicKey;

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

}