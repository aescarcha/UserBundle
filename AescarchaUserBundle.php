<?php

namespace Aescarcha\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AescarchaUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
