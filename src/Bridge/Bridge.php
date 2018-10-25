<?php

namespace GraphQLClientBundle\Bridge;

use GraphQLClientBundle\Logger\Logger;
use GraphQLClientPhp\Bridge\BridgeInterface;
use GraphQLClientPhp\Exception\BadResponseException;
use GraphQLClientPhp\Model\ApiModel;
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
        $this->preQuery($name, $query);
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

        $result = $response->getBody()->getContents();

        $decodedBody = json_decode($result);

        if (isset($decodedBody->errors)) {
            throw new BadResponseException(
                $this->parseResponseErrors($decodedBody->errors)
            );
        }

        $this->postQuery($name, $result);

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
        $this->preAsyncQuery();

        foreach ($queries as $key => $query) {
            $guzzleRequest = new Request(
                'POST',
                $this->model->getUri(),
                $this->getHeaders(),
                $query
            );
            $this->preQuery($key, $query);
            $promise = $client->sendAsync($guzzleRequest);
            $promise->then(
                function (ResponseInterface $res) use (&$payloads, $key) {
                    $result = $res->getBody()->getContents();
                    $this->postQuery($key, $result);
                    $payloads[$key] = json_decode($result)->data;
                },
                function (RequestException $e) use (&$payloads, $key) {
                    $result = json_encode([
                        ['message' => $this->parseResponseErrors([$e->getMessage()])],
                    ]);
                    $this->postQuery($key, $result);
                    $payloads[$key] = json_decode($result);
                }
            );
            $promises[$key] = $promise;
        }

        \GuzzleHttp\Promise\settle($promises)->wait();
        $this->postAsyncQuery();

        return $payloads;
    }

    /**
     * @param string $key
     * @param string $query
     */
    public function preQuery(string $key, string $query): void
    {
        $this->logger->start($key, $query);
    }

    /**
     * @param string $key
     * @param string $result
     */
    public function postQuery(string $key, string $result): void
    {
        $this->logger->stop($key, $result);
    }

    public function preAsyncQuery(): void
    {
        $this->logger->startAsync();
    }

    public function postAsyncQuery(): void
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
