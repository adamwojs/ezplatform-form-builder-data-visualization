<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReport\Core;

use AdamWojs\EzPlatformFormBuilderReport\SPI\ReportField\ReportFieldType;
use RuntimeException;

final class ReportFieldTypeRegistry
{
    /** @var \AdamWojs\EzPlatformFormBuilderReport\SPI\ReportField\ReportFieldType[] */
    private $fieldTypes;

    /**
     * @param \AdamWojs\EzPlatformFormBuilderReport\SPI\ReportField\ReportFieldType[] $fieldTypes
     */
    public function __construct()
    {
        $this->fieldTypes = [];
    }

    public function register(string $identifier, ReportFieldType $fieldType): void
    {
        $this->fieldTypes[$identifier] = $fieldType;
    }

    public function get(string $identifier): ReportFieldType
    {
        if ($this->has($identifier)) {
            return $this->fieldTypes[$identifier];
        }

        throw new RuntimeException(sprintf('Undefined %s for %s', ReportFieldType::class, $identifier));
    }

    public function has(string $identifier): bool
    {
        return isset($this->fieldTypes[$identifier]);
    }
}
