<?php

namespace Bethropolis\Bloom\Template;

class Path
{
    public function __construct($path)
    {
        define('BLOOM_TEMPLATE_PATH', $path);
    }
}
