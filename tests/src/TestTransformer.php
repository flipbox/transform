<?php

namespace Flipbox\Transform\Tests;

class TestTransformer
{
    public function __invoke($data)
    {
        return $data;
    }
}
