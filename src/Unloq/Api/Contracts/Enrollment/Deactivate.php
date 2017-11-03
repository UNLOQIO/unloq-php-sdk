<?php
namespace Unloq\Api\Contracts\Enrollment;

use Unloq\Api\Common\UnloqModel;

/**
 * Class Enroll
 *
 * @package Unloq\Api\Contracts
 *
 * @property string email                               - Required
 * @property string name                                - Optional
 */
class Deactivate extends UnloqModel {
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
}