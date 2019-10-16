<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReport\Core;

use ArrayIterator;
use EmptyIterator;
use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use EzSystems\EzPlatformFormBuilder\FieldType\Model\Field;
use EzSystems\EzPlatformFormBuilder\FieldType\Model\Form;
use EzSystems\EzPlatformFormBuilder\FieldType\Type;
use Guzzle\Iterator\FilterIterator;
use Iterator;

final class FormService
{
    public const ENABLE_SUMMARY_ATTRIBUTE = 'enable_summary';
    public const ENABLE_SUMMARY_VALUE = '1';

    /** @var \eZ\Publish\API\Repository\ContentTypeService */
    private $contentTypeService;

    /** @var \EzSystems\EzPlatformFormBuilder\FieldType\Type */
    private $formBuilderType;

    public function __construct(ContentTypeService $contentTypeService, Type $formBuilderType)
    {
        $this->contentTypeService = $contentTypeService;
        $this->formBuilderType = $formBuilderType;
    }

    public function isForm(ContentInfo $content): bool
    {
        $fieldDefinitions = $this->contentTypeService
            ->loadContentType($content->contentTypeId)
            ->getFieldDefinitions();

        foreach ($fieldDefinitions as $fieldDefinition) {
            if ($this->formBuilderType->getFieldTypeIdentifier() === $fieldDefinition->fieldTypeIdentifier) {
                return true;
            }
        }

        return false;
    }

    public function getSummaryFieldsIterator(Content $content): Iterator
    {
        $form = $this->findFormDefinition($content);
        if ($form === null) {
            return new EmptyIterator();
        }

        return new FilterIterator(
            new ArrayIterator($form->getFields()),
            function (Field $field): bool {
                if (!$field->hasAttribute(self::ENABLE_SUMMARY_ATTRIBUTE)) {
                    return false;
                }

                return $field->getAttributeValue(self::ENABLE_SUMMARY_ATTRIBUTE) === self::ENABLE_SUMMARY_VALUE;
            }
        );
    }

    private function findFormDefinition(Content $content): ?Form
    {
        foreach ($content->getFields() as $field) {
            if ($field->fieldTypeIdentifier === $this->formBuilderType->getFieldTypeIdentifier()) {
                return $field->value->getFormValue();
            }
        }

        return null;
    }
}
