<?php
namespace Unloq\Api\Contracts\Firewalls;

use Unloq\Api\Common\UnloqModel;

/**
 * Class Firewall
 *
 * @package Unloq\Api\Contracts
 *
 * @property string type ("ALLOW", "DENY")                  - Required
 * @property string ip_range                                - Optional
 * @property string country                                 - Optional
 * @property string region                                  - Optional
 * @property boolean is_logged                              - Optional
 * @property object auth                                    - Optional
 * @property boolean check_source                           - Optional
 * @property string entity_type                             - Optional
 * Available options for entity_type:
 * - ORGANIZATION, APPLICATION, MOBILE
 */
class Firewall extends UnloqModel {
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
    public function getIpRange()
    {
        return $this->ip_range;
    }

    /**
     * @param string $ipRange
     *
     * @return $this
     */
    public function setIpRange($ipRange)
    {
        $this->ip_range = $ipRange;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;

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
     * @return bool
     */
    public function isIsLogged()
    {
        return $this->is_logged;
    }

    /**
     * @param bool $isLogged
     *
     * @return $this
     */
    public function setIsLogged($isLogged)
    {
        $this->is_logged = $isLogged;

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

    /**
     * @return bool
     */
    public function isCheckSource()
    {
        return $this->check_source;
    }

    /**
     * @param bool $checkSource
     *
     * @return $this
     */
    public function setCheckSource($checkSource)
    {
        $this->check_source = $checkSource;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntityType()
    {
        return $this->entity_type;
    }

    /**
     * @param string $entityType
     *
     * @return $this
     */
    public function setEntityType($entityType)
    {
        $this->entity_type = $entityType;

        return $this;
    }

}