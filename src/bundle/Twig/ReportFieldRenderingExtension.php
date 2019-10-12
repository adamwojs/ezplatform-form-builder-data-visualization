<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReportBundle\Twig;

use AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportField;
use AdamWojs\EzPlatformFormBuilderReport\Core\ReportFieldBlockRenderer;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ReportFieldRenderingExtension extends AbstractExtension
{
    /** @var \AdamWojs\EzPlatformFormBuilderReport\Core\ReportFieldBlockRenderer */
    private $renderer;

    /**
     * @param \AdamWojs\EzPlatformFormBuilderReport\Core\ReportFieldBlockRenderer $renderer
     */
    public function __construct(ReportFieldBlockRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'ez_render_form_submission_report_field',
                function (Environment $twig, ReportField $field, array $parameters = []): string {
                    return $this->renderer->render($field, $parameters);
                },
                ['is_safe' => ['html'], 'needs_environment' => true]
            ),
            new TwigFunction(
                'ez_render_form_submission_report_field_id',
                function (ReportField $field): string {
                    return sha1($field->getName());
                },
                ['is_safe' => ['html']]
            ),
        ];
    }
}
