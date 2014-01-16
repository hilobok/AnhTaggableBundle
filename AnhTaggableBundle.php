<?php

namespace Anh\TaggableBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AnhTaggableBundle extends Bundle
{
    public static function getRequiredBundles()
    {
        return array(
            'Sp\BowerBundle\SpBowerBundle',
        );
    }
}
