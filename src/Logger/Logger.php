<?php

namespace GraphClientBundle\Logger;

class Logger
{
    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var array
     */
    private $storage;

    /**
     * @param bool $enabled
     */
    public function __construct(bool $enabled = true)
    {
        $this->enabled = $enabled;
        $this->storage = [];
    }

    /**
     * @param string $key
     * @param string $query
     *
     * @return int
     */
    public function start(string $key, string $query): int
    {
        if (!$this->isEnabled()) {
            return -1;
        }

        $id = count($this->storage);

        $this->storage[$key] = ['query' => json_decode($query), 'start_time' => microtime(true)];

        return $id;
    }

    /**
     * @param string $key
     * @param string $result
     */
    public function stop(string $key, string $result): void
    {
        if (!$this->isEnabled()) {
            return;
        }

        $this->storage[$key]['result'] = json_decode($result);
        $this->storage[$key]['end_time'] = microtime(true);
        $this->storage[$key]['duration'] = $this->storage[$key]['end_time'] - $this->storage[$key]['start_time'];
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return array_values($this->storage);
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
