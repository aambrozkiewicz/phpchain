<?php

namespace phpchain;

class MemoryCachedChainDispatcher extends ChainDispatcher
{
    private $cache = [];

    public function dispatch($code)
    {
        if (!empty($this->cache[$code])) {
            return $this->cache[$code];
        }

        return $this->cache[$code] = parent::dispatch($code);
    }
}
