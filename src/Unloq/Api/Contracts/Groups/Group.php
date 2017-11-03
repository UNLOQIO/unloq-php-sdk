<?php
namespace Unloq\Api\Contracts\Groups;

use Unloq\Api\Common\UnloqModel;

/**
 * Class Group
 *
 * @package Unloq\Api\Contracts
 *
 * @property string name                       - Optional
 * @property string description                - Optional
 * @property boolean is_default                - Optional
 */
class Group extends UnloqModel
{
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return bool
     */
    public function isIsDefault()
    {
        return $this->is_default;
    }

    /**
     * @param bool $isDefault
     *
     * @return $this
     */
    public function setIsDefault($isDefault)
    {
        $this->is_default = $isDefault;

        return $this;
    }

}