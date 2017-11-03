<?php
namespace Unloq\Api\Contracts\Widgets;

use Unloq\Api\Common\UnloqModel;

/**
 * Class Widget
 *
 * @package Unloq\Api\Contracts
 *
 * @property string environment (LIVE, SANDBOX)                 - Required
 * @property string name                                        - Optional
 * @property string lang                                        - Optional
 * @property string type                                        - Required
 * Available options for tye:
 * - LOGIN, CONFIRMATION, DEACTIVATE, REGISTER, LOGOUT
 * @property boolean is_active                                  - Optional
 * @property string url                                         - Required
 * @property string color_primary                               - Optional
 * @property string color_secondary                             - Optional
 * @property string color_link                                  - Optional
 * @property boolean has_watermark                              - Optional
 */
class Widget extends UnloqModel
{
    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param string $environment
     *
     * @return $this
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;

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

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param string $lang
     *
     * @return $this
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

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
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getColorPrimary()
    {
        return $this->color_primary;
    }

    /**
     * @param string $colorPrimary
     *
     * @return $this
     */
    public function setColorPrimary($colorPrimary)
    {
        $this->color_primary = $colorPrimary;

        return $this;
    }

    /**
     * @return string
     */
    public function getColorSecondary()
    {
        return $this->color_secondary;
    }

    /**
     * @param string $colorSecondary
     *
     * @return $this
     */
    public function setColorSecondary($colorSecondary)
    {
        $this->color_secondary = $colorSecondary;

        return $this;
    }

    /**
     * @return string
     */
    public function getColorLink()
    {
        return $this->color_link;
    }

    /**
     * @param string $colorLink
     *
     * @return $this
     */
    public function setColorLink($colorLink)
    {
        $this->color_link = $colorLink;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHasWatermark()
    {
        return $this->has_watermark;
    }

    /**
     * @param bool $hasWatermark
     *
     * @return $this
     */
    public function setHasWatermark($hasWatermark)
    {
        $this->has_watermark = $hasWatermark;

        return $this;
    }
}