<?php

declare(strict_types=1);

namespace OnMoon\OpenApiServerBundle\DependencyInjection;

use OnMoon\OpenApiServerBundle\Controller\ApiController;
use Override;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

use function array_keys;

final class CompilerPass implements CompilerPassInterface
{
    private string $tag;

    public function __construct(string $tag)
    {
        $this->tag = $tag;
    }

    #[Override]
    public function process(ContainerBuilder $container): void
    {
        if (! $container->has(ApiController::class)) {
            return;
        }

        $definition     = $container->findDefinition(ApiController::class);
        $taggedServices = $container->findTaggedServiceIds($this->tag);

        foreach (array_keys($taggedServices) as $id) {
            $definition->addMethodCall('setApiLoader', [new Reference($id)]);

            break;
        }
    }
}
