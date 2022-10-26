<?php

declare(strict_types=1);

namespace Knp\JsonSchemaBundle\OpenApi\Annotation;

use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;

class JsonSchema extends Schema
{
    /**
     * @var array<mixed>
     */
    public static $_required = ['schema'];

    /**
     * @param array{
     *  schema: class-string
     * } $properties
     */
    public function __construct(array $properties)
    {
        parent::__construct($properties);

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
                    fn (array $schema) => new Schema($schema),
                    $property['oneOf']
                );
            }

            return new Property(array_merge([
                'property' => $name,
            ], $property));
        }, array_keys($jsonSchema['properties']), $jsonSchema['properties']);

        $this->example = $jsonSchema['examples'] ?? [];
    }
}
