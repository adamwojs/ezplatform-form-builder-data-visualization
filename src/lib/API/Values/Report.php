<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReport\API\Values;

final class Report
{
    /** @var \AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportField[] */
    private $fields;

    /**
     * @param \AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportField[] $fields
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function getFields(): array
    {
        return $this->fields;
    }
}
