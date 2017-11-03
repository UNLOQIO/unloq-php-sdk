<?php
namespace Unloq\Api\Contracts\Firewalls;

use Unloq\Api\Common\UnloqModel;

/**
 * Class Log
 *
 * @package Unloq\Api\Contracts
 *
 * @property string target_id                      - Optional
 */
class Log extends UnloqModel {
    /**
     * @return string
     */
    public function getTargetId()
    {
        return $this->target_id;
    }

    /**
     * @param string $targetId
     *
     * @return $this
     */
    public function setTargetId($targetId)
    {
        $this->target_id = $targetId;

        return $this;
    }

}