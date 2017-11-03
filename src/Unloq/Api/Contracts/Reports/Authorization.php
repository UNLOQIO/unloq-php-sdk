<?php
namespace Unloq\Api\Contracts\Reports;

use Unloq\Api\Common\UnloqModel;

/**
 * Class Notification
 *
 * @package Unloq\Api\Contracts
 *
 * @property array group                                   - Optional
 * @property array ip                                      - Optional
 * @property array account                                 - Optional
 * @property array country                                 - Optional
 * @property array region                                  - Optional
 * @property array device_type (ANDROID, IOS)              - Optional
 * @property array device_version                          - Optional
 * @property array status                                  - Optional
 * @property array start_date                              - Optional
 * @property array end_date                                - Optional
 * @property array limit                                   - Optional
 * @property array page                                    - Optional
 * @property array action                                  - Optional
 * @property array reference                               - Optional
 *
 */
class Authorization extends UnloqModel
{
    /**
     * @return array
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param array $group
     *
     * @return $this
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return array
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param array $ip
     *
     * @return $this
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @return array
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param array $account
     *
     * @return $this
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return array
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param array $country
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return array
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param array $region
     *
     * @return $this
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return array
     */
    public function getDeviceType()
    {
        return $this->device_type;
    }

    /**
     * @param array $device_type
     *
     * @return $this
     */
    public function setDeviceType($device_type)
    {
        $this->device_type = $device_type;

        return $this;
    }

    /**
     * @return array
     */
    public function getDeviceVersion()
    {
        return $this->device_version;
    }

    /**
     * @param array $device_version
     *
     * @return $this
     */
    public function setDeviceVersion($device_version)
    {
        $this->device_version = $device_version;

        return $this;
    }

    /**
     * @return array
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param array $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return array
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @param array $start_date
     *
     * @return $this
     */
    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;

        return $this;
    }

    /**
     * @return array
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * @param array $end_date
     *
     * @return $this
     */
    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;

        return $this;
    }

    /**
     * @return array
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param array $limit
     *
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return array
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param array $page
     *
     * @return $this
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return array
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param array $action
     *
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return array
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param array $reference
     *
     * @return $this
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }
}