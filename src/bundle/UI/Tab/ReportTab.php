<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReportBundle\UI\Tab;

use AdamWojs\EzPlatformFormBuilderReport\API\FormSubmissionReportServiceInterface;
use eZ\Publish\API\Repository\ContentTypeService;
use EzSystems\EzPlatformAdminUi\Tab\AbstractTab;
use EzSystems\EzPlatformAdminUi\Tab\ConditionalTabInterface;
use EzSystems\EzPlatformAdminUi\Tab\OrderedTabInterface;
use EzSystems\EzPlatformFormBuilder\FieldType\Type;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

final class ReportTab extends AbstractTab implements OrderedTabInterface, ConditionalTabInterface
{
    private const TAB_IDENTIFIER = 'submissions_report';

    /** @var FormSubmissionReportServiceInterface */
    private $formSubmissionReportService;

    /** @var \eZ\Publish\API\Repository\ContentTypeService */
    private $contentTypeService;

    /** @var \EzSystems\EzPlatformFormBuilder\FieldType\Type */
    private $formBuilderType;

    public function __construct(
        Environment $twig,
        TranslatorInterface $translator,
        FormSubmissionReportServiceInterface $formSubmissionReportService,
        ContentTypeService $contentTypeService,
        Type $formBuilderType
    ) {
        parent::__construct($twig, $translator);

        $this->contentTypeService = $contentTypeService;
        $this->formBuilderType = $formBuilderType;
        $this->formSubmissionReportService = $formSubmissionReportService;
    }

    public function evaluate(array $parameters): bool
    {
        $location = $parameters['location'];

        $contentType = $this->contentTypeService->loadContentType(
            $location->getContentInfo()->contentTypeId
        );

        // TODO: Move to specification
        foreach ($contentType->getFieldDefinitions() as $fieldDefinition) {
            if ($this->formBuilderType->getFieldTypeIdentifier() === $fieldDefinition->fieldTypeIdentifier) {
                return true;
            }
        }

        return false;
    }

    public function getOrder(): int
    {
        return 120;
    }

    public function getIdentifier(): string
    {
        return self::TAB_IDENTIFIER;
    }

    public function getName(): string
    {
        return 'Results';
    }

    public function renderView(array $parameters): string
    {
        /** @var \eZ\Publish\API\Repository\Values\Content\Content $content */
        $content = $parameters['content'];

        $report = $this->formSubmissionReportService->generate($content->contentInfo);

        return $this->twig->render(
            '@ezdesign/content/tab/report/tab.html.twig',
            array_merge($parameters, [
                'report' => $report,
            ])
        );
    }
}
