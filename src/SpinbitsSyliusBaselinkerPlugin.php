<?php

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SpinbitsSyliusBaselinkerPlugin extends Bundle
{
    use SyliusPluginTrait;
}
