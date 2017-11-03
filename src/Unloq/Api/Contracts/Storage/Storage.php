<?php
namespace Unloq\Api\Contracts\Storage;

use Unloq\Api\Common\UnloqModel;

/**
 * Class Storage
 *
 * @package Unloq\Api\Contracts
 *
 * @property string type (AWS)                 - Required
 * @property string region                     - Required
 * @property string bucket                     - Required
 * @property object auth                       - Required
 */
class Storage extends UnloqModel {
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
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     *
     * @return $this
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return string
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * @param string $bucket
     *
     * @return $this
     */
    public function setBucket($bucket)
    {
        $this->bucket = $bucket;

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
     * @param object $auth
     *
     * @return $this
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;

        return $this;
    }

}