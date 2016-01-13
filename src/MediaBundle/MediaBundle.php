<?php

namespace MediaBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MediaBundle extends Bundle
{
    public function getParent()
    {
        return 'OktolabMediaBundle';
    }
}
