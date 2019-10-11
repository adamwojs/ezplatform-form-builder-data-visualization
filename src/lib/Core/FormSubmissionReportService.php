<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReport\Core;

use AdamWojs\EzPlatformFormBuilderReport\API\FormSubmissionReportServiceInterface;
use AdamWojs\EzPlatformFormBuilderReport\API\Values\Report;
use AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportBuilder;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use EzSystems\EzPlatformFormBuilder\FieldType\Model\FieldValue;
use EzSystems\EzPlatformFormBuilder\FieldType\Model\FormSubmission;
use EzSystems\EzPlatformFormBuilder\FormSubmission\FormSubmissionServiceInterface;

final class FormSubmissionReportService implements FormSubmissionReportServiceInterface
{
    /** @var \EzSystems\EzPlatformFormBuilder\FormSubmission\FormSubmissionServiceInterface */
    private $formSubmissionService;

    /** @var \AdamWojs\EzPlatformFormBuilderReport\Core\ReportFieldTypeRegistry */
    private $reportFieldTypeRegistry;

    public function __construct(
        FormSubmissionServiceInterface $formSubmissionService,
        ReportFieldTypeRegistry $reportFieldTypeRegistry
    ) {
        $this->formSubmissionService = $formSubmissionService;
        $this->reportFieldTypeRegistry = $reportFieldTypeRegistry;
    }

    public function generate(ContentInfo $content, ?string $languageCode = null): Report
    {
        $report = new ReportBuilder();

        $submissions = $this->formSubmissionService->loadByContent($content, $languageCode);
        foreach ($submissions as $submission) {
            $this->doUpdateReport($report, $submission);
        }

        return $report->build();
    }

    private function doUpdateReport(ReportBuilder $report, FormSubmission $submission): void
    {
        foreach ($submission->getValues() as $value) {
            $this->doUpdateReportField($report, $value);
        }
    }

    private function doUpdateReportField(ReportBuilder $report, FieldValue $value): void
    {
        $identifier = $value->getIdentifier();

        if ($this->reportFieldTypeRegistry->has($identifier)) {
            $type = $this->reportFieldTypeRegistry->get($identifier);
            $name = $value->getName();

            if (!$report->hasField($name)) {
                $report->addField($type->createEmpty($name));
            }

            $type->update($report->getField($name), $value);
        }
    }
}
