<?php

declare(strict_types=1);

namespace Knp\JsonSchemaBundle\OpenApi\Attributes;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use OpenApi\Generator;

class JsonSchema extends Schema
{
    /**
     * @param array{
     *  schema: class-string
     * } $properties
     */
    public function __construct(array $properties)
    {
        parent::__construct(...$properties);

        $schemaClass = $properties['schema'];
        $schema = new $schemaClass();

        $jsonSchema = $schema->jsonSerialize();

        $this->title = $jsonSchema['title'] ?? '';
        $this->description = $jsonSchema['description'] ?? '';
        $this->type = $jsonSchema['type'] ?? '';
        $this->required = $jsonSchema['required'] ?? false;
        $this->additionalProperties = $jsonSchema['additionalProperties'] ?? true;

        $this->properties = array_map(function ($name, array $property) {
            if (\array_key_exists('oneOf', $property)) {
                $property['oneOf'] = array_map(
                    fn (object $schema) => new Schema($schema),
                    $property['oneOf']
                );
            }

            return new Property(
                ...$property,
                property: $name,
            );
        }, array_keys($jsonSchema['properties']), $jsonSchema['properties']);


        $this->example = $jsonSchema['examples'] ?? [];
    }
}
{

}
