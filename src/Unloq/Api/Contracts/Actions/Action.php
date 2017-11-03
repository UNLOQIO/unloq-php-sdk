<?php
namespace Unloq\Api\Contracts\Actions;

use Unloq\Api\Common\UnloqModel;

/**
 * Class Action
 *
 * @package Unloq\Api\Contracts
 *
 * @property array code                           - Required
 * @property array title                          - Required
 * @property array message                        - Required
 * @property array approve                        - Optional
 * @property array deny                           - Optional
 */
class Action extends UnloqModel
{
    /**
     * @return array
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param array $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return array
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param array $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return array
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param array $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return array
     */
    public function getApprove()
    {
        return $this->approve;
    }

    /**
     * @param array $approve
     *
     * @return $this
     */
    public function setApprove($approve)
    {
        $this->approve = $approve;

        return $this;
    }

    /**
     * @return array
     */
    public function getDeny()
    {
        return $this->deny;
    }

    /**
     * @param array $deny
     *
     * @return $this
     */
    public function setDeny($deny)
    {
        $this->deny = $deny;

        return $this;
    }
}