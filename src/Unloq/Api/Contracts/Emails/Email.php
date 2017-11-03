<?php
namespace Unloq\Api\Contracts\Emails;

use Unloq\Api\Common\UnloqModel;

/**
 * Class Email
 *
 * @package Unloq\Api\Contracts
 *
 * @property string type (MAILGUN, AWS)                 - Required
 * @property string email_from                          - Required
 * @property string email_admin                         - Required
 * @property string email_reply                         - Optional
 * @property string from_name                           - Optional
 * @property array auth                                 - Optional
 * @property string domain                              - Optional
 */
class Email extends UnloqModel
{
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmailFrom()
    {
        return $this->email_from;
    }

    /**
     * @param string $emailFrom
     *
     * @return $this
     */
    public function setEmailFrom($emailFrom)
    {
        $this->email_from = $emailFrom;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmailAdmin()
    {
        return $this->email_admin;
    }

    /**
     * @param string $emailAdmin
     *
     * @return $this
     */
    public function setEmailAdmin($emailAdmin)
    {
        $this->email_admin = $emailAdmin;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmailReply()
    {
        return $this->email_reply;
    }

    /**
     * @param string $emailReply
     *
     * @return $this
     */
    public function setEmailReply($emailReply)
    {
        $this->email_reply = $emailReply;

        return $this;
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        return $this->from_name;
    }

    /**
     * @param string $from_name
     *
     * @return $this
     */
    public function setFromName($from_name)
    {
        $this->from_name = $from_name;

        return $this;
    }

    /**
     * @return object
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * @param array $auth
     *
     * @return $this
     */
    public function setAuth($auth)
    {
        $this->auth = json_encode($auth);

        return $this;
    }

    /**
     * @return object
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param array $auth
     *
     * @return $this
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }
}