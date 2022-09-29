<?php

declare(strict_types=1);

namespace Knp\JsonSchemaBundle;

use Knp\JsonSchemaBundle\DependencyInjection\JsonSchemaExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class JsonSchemaBundle extends AbstractBundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new JsonSchemaExtension();
    }
}
