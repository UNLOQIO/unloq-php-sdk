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
class Enroll extends UnloqModel {
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
}