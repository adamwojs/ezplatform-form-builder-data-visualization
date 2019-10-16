<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReport\Core;

use AdamWojs\EzPlatformFormBuilderReport\API\FormSubmissionReportServiceInterface;
use AdamWojs\EzPlatformFormBuilderReport\API\Values\Report;
use AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportBuilder;
use eZ\Publish\API\Repository\Values\Content\Content;
use EzSystems\EzPlatformFormBuilder\FieldType\Model\FieldValue;
use EzSystems\EzPlatformFormBuilder\FieldType\Model\FormSubmission;
use EzSystems\EzPlatformFormBuilder\FormSubmission\FormSubmissionServiceInterface;
use Iterator;

final class FormSubmissionReportService implements FormSubmissionReportServiceInterface
{
    /** @var \EzSystems\EzPlatformFormBuilder\FormSubmission\FormSubmissionServiceInterface */
    private $formSubmissionService;

    /** @var \AdamWojs\EzPlatformFormBuilderReport\Core\FormService */
    private $formService;

    /** @var \AdamWojs\EzPlatformFormBuilderReport\Core\ReportFieldTypeRegistry */
    private $reportFieldTypeRegistry;

    public function __construct(
        FormSubmissionServiceInterface $formSubmissionService,
        FormService $formService,
        ReportFieldTypeRegistry $reportFieldTypeRegistry
    ) {
        $this->formSubmissionService = $formSubmissionService;
        $this->formService = $formService;
        $this->reportFieldTypeRegistry = $reportFieldTypeRegistry;
    }

    public function generate(Content $content, ?string $languageCode = null): Report
    {
        $report = new ReportBuilder();

        $submissions = $this->formSubmissionService->loadByContent($content->contentInfo, $languageCode);
        if ($submissions->getTotalCount() > 0) {
            $iterator = $this->formService->getSummaryFieldsIterator($content);

            foreach ($submissions as $submission) {
                $this->doUpdateReport($iterator, $report, $submission);
            }
        }

        return $report->build();
    }

    private function doUpdateReport(Iterator $fields, ReportBuilder $report, FormSubmission $submission): void
    {
        foreach ($fields as $field) {
            foreach ($submission->getValues() as $value) {
                if ($value->getName() === $field->getName()) {
                    $this->doUpdateReportField($report, $value);
                }
            }
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
