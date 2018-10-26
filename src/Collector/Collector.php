<?php

namespace GraphClientBundle\Collector;

use GraphClientBundle\Logger\Logger;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Collector extends DataCollector
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Request         $request
     * @param Response        $response
     * @param \Exception|null $exception
     */
    public function collect(
        Request $request,
        Response $response,
        \Exception $exception = null
    ): void {
        $this->data = [
            'queries' => $this->logger->getAll(),
            'logger_enabled' => $this->logger->isEnabled(),
        ];
    }

    /**
     * @return array
     */
    public function getQueries(): array
    {
        return $this->data['queries'];
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return count($this->getQueries());
    }

    /**
     * @return float
     */
    public function getTotalTime(): float
    {
        $total = array_reduce($this->getQueries(), function ($carry, $item) {
            $total = $carry;
            if (!empty($item['duration'])) {
                $total += $item['duration'];
            }

            return $total;
        });

        return round($total * 1000, 2);
    }

    /**
     * @return bool
     */
    public function isLoggerEnabled(): bool
    {
        return $this->data['logger_enabled'];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'graph-client-bundle.collector';
    }

    public function reset(): void
    {
        $this->data = [];
    }
}
