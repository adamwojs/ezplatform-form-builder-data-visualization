<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportField;

use AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportField;

final class StatisticsReportField extends ReportField
{
    /** @var float */
    private $sum;

    /** @var int */
    private $count;

    /** @var float */
    private $min;

    /** @var float */
    private $max;

    public function __construct(
        string $identifier,
        float $sum = 0.0,
        int $count = 0,
        float $min = PHP_FLOAT_MAX,
        float $max = PHP_FLOAT_MIN
    ) {
        parent::__construct($identifier);

        $this->sum = $sum;
        $this->count = $count;
        $this->min = $min;
        $this->max = $max;
    }

    public function getSum(): float
    {
        return $this->sum;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getMin(): float
    {
        return $this->min;
    }

    public function getMax(): float
    {
        return $this->max;
    }

    /**
     * @internal
     */
    public function update(float $value): self
    {
        $this->sum += $value;
        ++$this->count;
        $this->min = min($this->min, $value);
        $this->max = max($this->max, $value);

        return $this;
    }
}
