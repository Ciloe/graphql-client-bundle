<?php

namespace GraphClientBundle\Bridge;

use GraphClientBundle\Logger\Logger;
use GraphClientPhp\Bridge\BridgeInterface;
use GraphClientPhp\Exception\BadResponseException;
use GraphClientPhp\Model\ApiModel;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

class Bridge implements BridgeInterface
{
    /**
     * @var ApiModel
     */
    private $model;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $query;

    /**
     * @var string
     */
    private $result;

    /**
     * @param ApiModel $model
     * @param Logger   $logger
     */
    public function __construct(ApiModel $model, Logger $logger)
    {
        $this->model = $model;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     *
     * @throws BadResponseException|\GuzzleHttp\Exception\GuzzleException
     */
    public function query(string $name, string $query): \stdClass
    {
        $this->key = $name;
        $this->query = $query;
        $this->postQuery();
        $headers = $this->getHeaders();

        $client = new Client(['base_uri' => $this->model->getHost()]);
        $response = $client->request(
            'POST',
            $this->model->getUri(),
            [
                'body' => $query,
                'headers' => $headers,
            ]
        );

        $this->result = $response->getBody()->getContents();

        $decodedBody = json_decode($this->result);

        if (isset($decodedBody->errors)) {
            throw new BadResponseException(
                $this->parseResponseErrors($decodedBody->errors)
            );
        }
        $this->preQuery();

        return $decodedBody->data;
    }

    /**
     * {@inheritdoc}
     */
    public function queryAsync(array $queries): array
    {
        $promises = [];
        $payloads = [];

        $client = new Client(['base_uri' => $this->model->getHost()]);
        $this->postAsyncQuery();
        foreach ($queries as $key => $query) {
            $guzzleRequest = new Request(
                'POST',
                $this->model->getUri(),
                $this->getHeaders(),
                $query
            );
            $this->key = $key;
            $this->query = $query;
            $this->postQuery();
            $promise = $client->sendAsync($guzzleRequest);
            $promise->then(
                function (ResponseInterface $res) use (&$payloads, $key) {
                    $this->result = $res->getBody()->getContents();
                    $this->preQuery();
                    $payload = json_decode($this->result);
                    $payloads[$key] = $payload;
                },
                function (RequestException $e) use (&$payloads, $key) {
                    $result = [
                        ['message' => $this->parseResponseErrors([$e->getMessage()])],
                    ];
                    $this->result = json_encode($result);
                    $this->preQuery();
                    $payloads[$key] = $result;
                }
            );
            $promises[$key] = $promise;
        }

        \GuzzleHttp\Promise\settle($promises)->wait();

        $this->preAsyncQuery();

        return $payloads;
    }

    /**
     * {@inheritdoc}
     */
    public function postQuery(): void
    {
        $this->logger->start($this->key, $this->query);
    }

    /**
     * {@inheritdoc}
     */
    public function preQuery(): void
    {
        $this->logger->stop($this->key, $this->result);
    }

    /**
     * @param string $key
     * @param string $query
     */
    public function postAsyncQuery(): void
    {
        $this->logger->startAsync();
    }

    /**
     * @param string $key
     * @param string $result
     */
    public function preAsyncQuery(): void
    {
        $this->logger->stopAsync();
    }

    /**
     * @return array
     */
    protected function getHeaders(): array
    {
        return [
            'content-type' => 'application/json',
            'authorization' => sprintf('Bearer %s', $this->model->getToken()),
        ];
    }

    /**
     * @param array $errors
     *
     * @return string
     */
    protected function parseResponseErrors(array $errors): string
    {
        $errorMessage = 'Wrong call to graphAPI cause:';

        foreach ($errors as $error) {
            $errorMessage .= ' ==> ' . $error->message . '\n';
        }

        return $errorMessage;
    }
}
