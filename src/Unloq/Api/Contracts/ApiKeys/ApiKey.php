<?php
namespace Unloq\Api\Contracts\ApiKeys;

use Unloq\Api\Common\UnloqModel;

/**
 * Class Key
 *
 * @package Unloq\Api\Contracts
 *
 * @property array scopes                           - Required
 */
class ApiKey extends UnloqModel
{
    /**
     * @return array
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * @param array $scopes
     *
     * @return $this
     */
    public function setScopes($scopes)
    {
        $this->scopes = $scopes;

        return $this;
    }

}