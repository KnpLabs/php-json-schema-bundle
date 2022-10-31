<?php

declare(strict_types=1);

namespace KnpLabs\JsonSchemaBundle\Validator;

use KnpLabs\JsonSchema\JsonSchemaInterface;
use KnpLabs\JsonSchema\Validator;
use KnpLabs\JsonSchema\Validator\Errors;
use KnpLabs\JsonSchema\Validator\Error as KnpJsonSchemaError;
use Swaggest\JsonSchema\Exception\Error;
use Swaggest\JsonSchema\Exception\LogicException;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\Schema;

class SwaggestValidator implements Validator
{
    public function validate(array $data, JsonSchemaInterface $schema): ?Errors
    {
        $schema = Schema::import(
            json_decode(
                json_encode(
                    $schema,
                    flags: JSON_THROW_ON_ERROR,
                ),
                flags: JSON_THROW_ON_ERROR,
            )
        );

        /**
         * @var mixed
         */
        $data = json_decode(
            json_encode(
                $data,
                flags: JSON_THROW_ON_ERROR,
            ),
            flags: JSON_THROW_ON_ERROR
        );

        try {
            $schema->in($data);

            return null;
        } catch (InvalidValue $invalidValue) {
            return new Errors(
                ...$this->yieldErrors($invalidValue)
            );
        }
    }

    /**
     * @return iterable<KnpJsonSchemaError>
     */
    private function yieldErrors(InvalidValue $invalidValue): iterable
    {
        /**
         * @var Error
         */
        $inspectionError = $invalidValue->inspect();

        /**
         * @var string
         */
        $errorMessage = $invalidValue->error;

        yield new KnpJsonSchemaError(
            $inspectionError->dataPointer,
            $errorMessage,
        );

        if ($invalidValue instanceof LogicException) {
            foreach ($invalidValue->subErrors as $subError) {
                yield from $this->yieldErrors($subError);
            }
        }
    }
}
