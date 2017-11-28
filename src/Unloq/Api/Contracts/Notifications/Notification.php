<?php
namespace Unloq\Api\Contracts\Notifications;

use Unloq\Api\Common\UnloqModel;

/**
 * Class Notification
 *
 * @package Unloq\Api\Contracts
 *
 * @property string name                                    - Required
 * @property string type (SIMPLE, SURVEY, RADIO, CHECKBOX)  - Required
 * @property string title                                   - Required
 * @property string content                                 - Required
 * @property integer level                                  - Required
 * @property boolean broadcast                              - Required
 * @property array receivers                                - Required
 * @property array groups                                   - Required
 * @property string close_text                              - Required
 * @property string submit_text                             - Required
 * @property integer send_at                                - Required
 * @property array radio                                    - Required
 * @property array checkbox                                 - Required
 */
class Notification extends UnloqModel
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *

    return $this;
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param int $level
     *
     * @return $this
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBroadcast()
    {
        return $this->broadcast;
    }

    /**
     * @param bool $broadcast
     *
     * @return $this
     */
    public function setBroadcast($broadcast)
    {
        $this->broadcast = $broadcast;

        return $this;
    }

    /**
     * @return array
     */
    public function getReceivers()
    {
        return $this->receivers;
    }

    /**
     * @param array $receivers
     *
     * @return $this
     */
    public function setReceivers($receivers)
    {
        $this->receivers = $receivers;

        return $this;
    }

    /**
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param array $groups
     *
     * @return $this
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @return string
     */
    public function getCloseText()
    {
        return $this->close_text;
    }

    /**
     * @param string $closeText
     *
     * @return $this
     */
    public function setCloseText($closeText)
    {
        $this->close_text = $closeText;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubmitText()
    {
        return $this->submit_text;
    }

    /**
     * @param string $submitText
     *
     * @return $this
     */
    public function setSubmitText($submitText)
    {
        $this->submit_text = $submitText;

        return $this;
    }

    /**
     * @return int
     */
    public function getSendAt()
    {
        return $this->send_at;
    }

    /**
     * @param int $sendAt
     *
     * @return $this
     */
    public function setSendAt($sendAt)
    {
        $this->send_at = $sendAt;

        return $this;
    }

    /**
     * @return array
     */
    public function getRadio()
    {
        return $this->radio;
    }

    /**
     * @param array $radio
     *
     * @return $this
     */
    public function setRadio($radio)
    {
        $this->radio = $radio;

        return $this;
    }

    /**
     * @return array
     */
    public function getCheckbox()
    {
        return $this->checkbox;
    }

    /**
     * @param array $checkbox
     *
     * @return $this
     */
    public function setCheckbox($checkbox)
    {
        $this->checkbox = $checkbox;

        return $this;
    }
}