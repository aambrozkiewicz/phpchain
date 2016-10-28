<?php

namespace phpchain;

interface ChainProvider
{
    /**
     * Register chain definitions into dispatcher
     *
     * @param ChainDispatcher $dispatcher
     * @return mixed
     */
    function register(ChainDispatcher $dispatcher);
}
