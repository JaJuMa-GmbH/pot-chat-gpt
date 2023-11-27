<?php
declare(strict_types=1);

namespace Jajuma\PotChatGpt\Block;

use Jajuma\PotChatGpt\Helper\Config as HelperConfig;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class QuickAction extends \Jajuma\PowerToys\Block\PowerToys\QuickAction
{
    /**
     * @var HelperConfig
     */
    protected HelperConfig $helperConfig;

    /**
     * @param Context $context
     * @param HelperConfig $helperConfig
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        HelperConfig $helperConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helperConfig = $helperConfig;
    }

    /**
     * Is enable
     *
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->helperConfig->isEnable();
    }
}
