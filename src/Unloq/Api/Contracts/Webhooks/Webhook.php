<?php
namespace Unloq\Api\Contracts\Webhooks;

use Unloq\Api\Common\UnloqModel;

/**
 * Class Webhook
 *
 * @package Unloq\Api\Contracts
 *
 * @property string event                               - Required
 * @property boolean raw_url                            - Optional
 * @property string url                                 - Optional
 * @property string secret                              - Optional
 * @property integer retry                              - Optional
 * @property integer retry_timeout                      - Optional
 * @property boolean is_active                          - Optional
 */
class Webhook extends UnloqModel
{
    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param string $event
     *
     * @return $this
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRawUrl()
    {
        return $this->raw_url;
    }

    /**
     * @param bool $rawUrl
     *
     * @return $this
     */
    public function setRawUrl($rawUrl)
    {
        $this->raw_url = $rawUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     *
     * @return $this
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * @return int
     */
    public function getRetry()
    {
        return $this->retry;
    }

    /**
     * @param int $retry
     *
     * @return $this
     */
    public function setRetry($retry)
    {
        $this->retry = $retry;

        return $this;
    }

    /**
     * @return int
     */
    public function getRetryTimeout()
    {
        return $this->retry_timeout;
    }

    /**
     * @param int $retryTimeout
     *
     * @return $this
     */
    public function setRetryTimeout($retryTimeout)
    {
        $this->retry_timeout = $retryTimeout;

        return $this;
    }

    /**
     * @return bool
     */
    public function isIsActive()
    {
        return $this->is_active;
    }

    /**
     * @param bool $isActive
     *
     * @return $this
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;

        return $this;
    }
}