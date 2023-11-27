<?php
declare(strict_types=1);

namespace Jajuma\PotChatGpt\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Config extends AbstractHelper
{
    public const XML_PATH_ENABLE = 'power_toys/pot_chat_gpt/is_enabled';

    public const XML_PATH_API_KEY = 'power_toys/pot_chat_gpt/api_key';

    public const XML_PATH_BASE_URL = 'power_toys/pot_chat_gpt/base_url';

    public const XML_PATH_MODEL = 'power_toys/pot_chat_gpt/model';

    /**
     * Is enable
     *
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLE);
    }

    /**
     * Get api key
     *
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_API_KEY);
    }

    /**
     * Get base url
     *
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_BASE_URL);
    }

    /**
     * Get model
     *
     * @return mixed
     */
    public function getModel()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_MODEL);
    }
}
