<?php

namespace GraphQLClientBundle\Logger;

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
     * @var string|null
     */
    private $asyncKey = null;

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
     */
    public function start(string $key, string $query): void
    {
        if (!$this->isEnabled()) {
            return ;
        }

        if (!is_null($this->asyncKey)) {
            $this->addStart(
                $this->storage[$this->asyncKey]['queries'],
                $key,
                $query
            );
        } else {
            $this->addStart(
                $this->storage,
                $key,
                $query
            );
        }
    }

    public function startAsync(): void
    {
        if (!$this->isEnabled()) {
            return ;
        }
        $this->asyncKey = count($this->storage);
        $this->storage[$this->asyncKey] = [
            'queries' => [],
            'type' => 'async',
            'start_time' => microtime(true)
        ];
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

        if (!is_null($this->asyncKey)) {
            $this->addStop(
                $this->storage[$this->asyncKey]['queries'],
                $key,
                $result
            );
        } else {
            $this->addStop(
                $this->storage,
                $key,
                $result
            );
        }
    }

    public function stopAsync(): void
    {
        if (!$this->isEnabled()) {
            return;
        }

        $this->storage[$this->asyncKey]['end_time'] = microtime(true);
        $this->storage[$this->asyncKey]['duration'] = $this->storage[$this->asyncKey]['end_time'] -
            $this->storage[$this->asyncKey]['start_time'];
        $this->asyncKey = null;
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

    /**
     * @param array  $array
     * @param string $key
     * @param string $query
     */
    private function addStart(array &$array, string $key, string $query): void
    {
        $array[$key] = [
            'type' => 'sync',
            'query' => json_decode($query),
            'start_time' => microtime(true)
        ];
    }

    /**
     * @param array  $array
     * @param string $key
     * @param string $result
     */
    private function addStop(array &$array, string $key, string $result): void
    {
        $array[$key]['end_time'] = microtime(true);
        $array[$key] = array_merge(
            $array[$key],
            [
                'result' => json_decode($result),
                'duration' => $array[$key]['end_time'] - $array[$key]['start_time']
            ]
        );
    }
}
