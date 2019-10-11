<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReportBundle;

use AdamWojs\EzPlatformFormBuilderReportBundle\DependencyInjection\CompilerPass\ReportFieldTypeRegistryCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class EzPlatformFormBuilderReportBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new ReportFieldTypeRegistryCompilerPass());
    }
}
