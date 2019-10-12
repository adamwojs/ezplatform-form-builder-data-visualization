<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReport\Core;

use AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportField;
use AdamWojs\EzPlatformFormBuilderReport\Core\Exception\MissingReportFieldBlockException;
use Symfony\Component\Form\Util\StringUtil;
use Twig\Environment;
use Twig\TemplateWrapper;

final class ReportFieldBlockRenderer
{
    private const BLOCK_NAME = 'ez_form_builder_%s';

    /** @var \Twig\Environment */
    private $twig;

    /** @var string[] */
    private $resources;

    public function __construct(Environment $twig, array $resources = [])
    {
        $this->twig = $twig;
        $this->resources = $resources;
    }

    public function render(ReportField $field, array $parameters = []): string
    {
        $blockName = $this->resolveBlockName($field);

        $localTemplate = null;
        if (isset($parameters['template'])) {
            $localTemplate = $parameters['template'];
            unset($parameters['template']);
        }

        $template = $this->findTemplateWithBlock($blockName, $localTemplate);
        if ($template === null) {
            throw new MissingReportFieldBlockException(sprintf('Could not find block for %s: %s!', get_class($field), $blockName));
        }

        return $template->renderBlock($blockName, $parameters + [
            'field' => $field,
        ]);
    }

    public function setResources(array $resources): void
    {
        usort($resources, function ($a, $b) {
            return $b['priority'] - $a['priority'];
        });

        $this->resources = array_column($resources, 'template');
    }

    /**
     * Find the first template containing block definition $blockName.
     *
     * @param string $blockName
     * @param string|\Twig\Template $localTemplate
     *
     * @return \Twig\TemplateWrapper|null
     */
    private function findTemplateWithBlock(string $blockName, $localTemplate = null): ?TemplateWrapper
    {
        if ($localTemplate !== null) {
            if (is_string($localTemplate)) {
                $localTemplate = $this->twig->load($localTemplate);
            }

            if ($localTemplate->hasBlock($blockName)) {
                return $localTemplate;
            }
        }

        foreach ($this->resources as &$template) {
            if (is_string($template)) {
                // Load the template if it is necessary
                $template = $this->twig->load($template);
            }

            if ($template->hasBlock($blockName)) {
                return $template;
            }
        }

        return null;
    }

    private function resolveBlockName(ReportField $field): string
    {
        return sprintf(self::BLOCK_NAME, StringUtil::fqcnToBlockPrefix(get_class($field)));
    }
}
