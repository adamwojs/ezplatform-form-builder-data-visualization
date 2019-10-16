<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReportBundle\UI\Tab;

use AdamWojs\EzPlatformFormBuilderReport\API\FormSubmissionReportServiceInterface;
use AdamWojs\EzPlatformFormBuilderReport\Core\FormService;
use eZ\Publish\API\Repository\LanguageService;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\Language;
use EzSystems\EzPlatformAdminUi\Tab\AbstractTab;
use EzSystems\EzPlatformAdminUi\Tab\ConditionalTabInterface;
use EzSystems\EzPlatformAdminUi\Tab\OrderedTabInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;

final class ReportTab extends AbstractTab implements OrderedTabInterface, ConditionalTabInterface
{
    public const URI_FRAGMENT = 'ez-tab-location-view-submission-summary';

    private const TAB_IDENTIFIER = 'submission-summary';

    /** @var \AdamWojs\EzPlatformFormBuilderReport\Core\FormService */
    private $formService;

    /** @var \AdamWojs\EzPlatformFormBuilderReport\API\FormSubmissionReportServiceInterface */
    private $formSubmissionReportService;

    /** @var \eZ\Publish\API\Repository\LanguageService */
    private $languageService;

    /** @var array */
    private $siteAccessLanguages;

    public function __construct(
        Environment $twig,
        TranslatorInterface $translator,
        FormSubmissionReportServiceInterface $formSubmissionReportService,
        FormService $formService,
        LanguageService $languageService,
        array $siteAccessLanguages
    ) {
        parent::__construct($twig, $translator);

        $this->formSubmissionReportService = $formSubmissionReportService;
        $this->formService = $formService;
        $this->languageService = $languageService;
        $this->siteAccessLanguages = $siteAccessLanguages;
    }

    public function evaluate(array $parameters): bool
    {
        return $this->formService->isForm($parameters['location']->getContentInfo());
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

        $report = $this->formSubmissionReportService->generate(
            $content,
            $content->prioritizedFieldLanguageCode
        );

        return $this->twig->render(
            '@ezdesign/content/tab/report/tab.html.twig',
            array_merge($parameters, [
                'report' => $report,
                'languages' => $this->loadContentLanguages($content),
            ])
        );
    }

    /**
     * Loads system languages with filtering applied.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return array
     */
    private function loadContentLanguages(Content $content): array
    {
        $contentLanguages = $content->versionInfo->languageCodes;

        $filter = function (Language $language) use ($contentLanguages) {
            return $language->enabled && in_array($language->languageCode, $contentLanguages, true);
        };

        $languagesByCode = [];
        foreach (array_filter($this->languageService->loadLanguages(), $filter) as $language) {
            $languagesByCode[$language->languageCode] = $language;
        }

        $saLanguages = [];
        foreach ($this->siteAccessLanguages as $languageCode) {
            if (!isset($languagesByCode[$languageCode])) {
                continue;
            }

            $saLanguages[] = $languagesByCode[$languageCode];
            unset($languagesByCode[$languageCode]);
        }

        return array_merge($saLanguages, array_values($languagesByCode));
    }
}
