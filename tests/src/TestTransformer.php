<?php

namespace flipbox\transform\tests;

class TestTransformer
{
    public function __invoke($data)
    {
        return $data;
    }
}
