<?php
namespace Unloq\Api\Contracts\Firewalls;

use Unloq\Api\Common\UnloqModel;

/**
 * Class Target
 *
 * @package Unloq\Api\Contracts
 *
 * @property string target_type                      - Required
 * Available options for target_type:
 * - ALL, DEVICE, API_KEY, TOKEN_KEY, APPROVAL, AUTHENTICATION, AUTHORIZATION, ENCRYPTION_KEY
 */
class Target extends UnloqModel {
    /**
     * @return string
     */
    public function getTargetType()
    {
        return $this->target_type;
    }

    /**
     * @param string $targetType
     *
     * @return $this
     */
    public function setTargetType($targetType)
    {
        $this->target_type = $targetType;

        return $this;
    }

}