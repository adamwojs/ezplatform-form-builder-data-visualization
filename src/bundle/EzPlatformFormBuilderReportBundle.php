<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReportBundle;

use AdamWojs\EzPlatformFormBuilderReportBundle\DependencyInjection\CompilerPass\ReportFieldTypeRegistryCompilerPass;
use AdamWojs\EzPlatformFormBuilderReportBundle\DependencyInjection\Configuration\Parser\ReportFieldTemplates;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class EzPlatformFormBuilderReportBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new ReportFieldTypeRegistryCompilerPass());

        $extension = $container->getExtension('ezpublish');
        $extension->addConfigParser(new ReportFieldTemplates());
        $extension->addDefaultSettings(__DIR__ . '/Resources/config', ['default_settings.yml']);
    }
}
