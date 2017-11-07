<?php
namespace Unloq\Api;

use GuzzleHttp\Client;
use Unloq\Constants\Environment;

class Base
{
    protected $client;

    public $apiKey;
    public $payload;
    public $verb;
    public $endpoint;
    public $clientErrorCode;
    public $clientErrorMessage;

    public function __construct()
    {
        $this->client = new Client();
        $this->endpoint = Environment::ENDPOINT_PROD;
    }

    /**
     * Executes an http request to UNLOQ REST API
     *
     * @param $verb
     * @param $action
     * @param null $payload
     *
     * @return object
     */
    public function execute($verb, $action, $payload = null)
    {
        $errorCode = null;
        $errorMessage = null;

        try {
            $response = $this->client->request($verb, $this->endpoint . $action, $this->setData($payload));

            if($response->getStatusCode() === 200){
                $code = $response->getStatusCode();
                $message = json_decode($response->getBody()->getContents());
            }
        } catch (\GuzzleHttp\Exception\ClientException $e){
            $code = $e->getCode();
            $message = json_decode($e->getResponse()->getBody()->getContents());

            if(isset($message->error) && isset($message->error->code)) {
                $errorCode = $message->error->code;
                $errorMessage = $message->error->message;
            } else {
                $errorCode = 'SERVER.ERROR';
                $errorMessage = 'An unexpected error occurred';
            }
        } catch (\GuzzleHttp\Exception\ServerException $e){
            $code = $e->getCode();
            $message = json_decode($e->getResponse()->getBody()->getContents());
            $errorCode = 'SERVER.ERROR';
            $errorMessage = 'An unexpected error occurred';
        } catch (\GuzzleHttp\Exception\RequestException $e){
            $code = $e->getCode();
            $message = 'An unexpected network error occurred';
            $errorCode = 'NETWORK.ERROR';
            $errorMessage = 'An unexpected network error occurred';
        }

        $return = [
            'httpCode' => $code,
            'responseMessage' => $message,
        ];

        if(isset($errorCode))
            $return['errorCode'] = $errorCode;
        if(isset($errorMessage))
            $return['errorMessage'] = $errorMessage;

        return (object)$return;
    }

    public function setData($payload)
    {
        $data = [];

        if(is_object($payload)) {
            $data = [
                'json' => (array) $payload,
            ];
        }

        $data['headers'] = ['Content-Type' => 'application/json'];

        if (isset($this->apiKey))
            $data['headers'] = ['Authorization' => 'Bearer ' . $this->apiKey];

        return $data;
    }
}