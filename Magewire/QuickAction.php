<?php
declare(strict_types=1);

namespace Jajuma\PotChatGpt\Magewire;

use Magewirephp\Magewire\Component;
use Jajuma\PotChatGpt\Helper\Config as HelperConfig;
use Magento\Framework\Serialize\Serializer\Json as JsonSerialize;

class QuickAction extends Component
{
    protected $loader = false;

    protected $listeners = ['requestApi'];

    /**
     * @var HelperConfig
     */
    protected HelperConfig $helperConfig;

    /**
     * @var JsonSerialize
     */
    protected JsonSerialize $jsonSerialize;

    /**
     * @param HelperConfig $helperConfig
     * @param JsonSerialize $jsonSerialize
     */
    public function __construct(
        HelperConfig $helperConfig,
        JsonSerialize $jsonSerialize
    ) {
        $this->helperConfig = $helperConfig;
        $this->jsonSerialize = $jsonSerialize;
    }

    /**
     * Request api
     *
     * @return void
     */
    public function requestApi($request)
    {
        $requestMessage = '';
        if (is_array($request)) {
            if (!empty($request['request_message'])) {
                $requestMessage = $request['request_message'];
            }
        } else {
            $requestMessage = $request;
        }

        if (empty($requestMessage)) {
            $message = 'Chat GPT returned an error or timed out. Please try again later!';
            $this->dispatchBrowserEvent('finish-request-chat-gpt-api', ['message' => nl2br($message)]);
            return;
        }

        $apiKey = $this->helperConfig->getApiKey();
        $baseUrl = $this->helperConfig->getBaseUrl();
        $model = $this->helperConfig->getModel();

        $data = [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $requestMessage
                ]
            ],
            'temperature' => 0
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $baseUrl);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->jsonSerialize->serialize($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if (!$response) {
            $message = 'Chat GPT returned an error or timed out. Please try again later!';
            $this->dispatchBrowserEvent('finish-request-chat-gpt-api', ['message' => nl2br($message)]);
            return;
        }

        $result = json_decode($response, true);
        if (!empty($result['error'])) {
            $message = $result['error']['message'];
            $this->dispatchBrowserEvent('finish-request-chat-gpt-api', ['message' => $message]);
            return;
        }

        $message = $result['choices'][0]['message']['content'];
        $this->dispatchBrowserEvent('finish-request-chat-gpt-api', ['message' => nl2br($message)]);
    }
}
