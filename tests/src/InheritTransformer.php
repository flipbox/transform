<?php

namespace Flipbox\Transform\Tests;

use Flipbox\Transform\Transformers\AbstractTransformer;

class InheritTransformer extends AbstractTransformer
{
    public function __invoke($data)
    {
        return [
            'foo' => $this->item(
                new \DateTime(),
                function(\DateTime $dt) {
                    var_dump($dt);
                }
            )
        ];
    }
}
