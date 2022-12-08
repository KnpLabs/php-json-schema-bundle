<?php

declare(strict_types=1);

namespace KnpLabs\JsonSchemaBundle\OpenApi\Attributes;

use KnpLabs\JsonSchema\JsonSchemaInterface;
use OpenApi\Attributes\JsonContent as AttributesJsonContent;

class JsonContent extends AttributesJsonContent
{
    /**
     * @param class-string $schema
     */
    public function __construct(string $schemaClass)
    {
        /**
         * @var JsonSchemaInterface
         */
        $schema = new $schemaClass();

        parent::__construct(
                schema: json_encode($schema->jsonSerialize()),
                example: iterator_to_array($schema->getExamples()),
                title: $schema->getTitle(),
                description: $schema->getDescription(),
        );
    }
}
