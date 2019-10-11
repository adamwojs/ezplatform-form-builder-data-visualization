<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportField;

use AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportField;

final class FrequencyReportField extends ReportField
{
    /** @var int[] */
    private $values;

    public function __construct(string $identifier, array $values = [])
    {
        parent::__construct($identifier);

        $this->values = $values;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @internal
     */
    public function update(array $choices): self
    {
        foreach ($choices as $choice) {
            $this->values[$choice] = ($this->values[$choice] ?? 0) + 1;
        }

        return $this;
    }
}
