<?php
namespace Unloq\Api\Contracts\Emails;

use Unloq\Api\Common\UnloqModel;

/**
 * Class GetTemplate
 *
 * @package Unloq\Api\Contracts
 *
 * @property string lang                             - Optional
 */
class GetTemplate extends UnloqModel {
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
}