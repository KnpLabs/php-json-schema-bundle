<?php

declare(strict_types=1);

namespace KnpLabs\JsonSchemaBundle\Validator;

use KnpLabs\JsonSchema\JsonSchemaInterface;
use KnpLabs\JsonSchema\Validator;
use KnpLabs\JsonSchema\Validator\Error as KnpJsonSchemaError;
use KnpLabs\JsonSchema\Validator\Errors;
use Opis\JsonSchema\Errors\ErrorFormatter;
use Opis\JsonSchema\Errors\ValidationError;
use Opis\JsonSchema\Validator as JsonSchemaValidator;

class OpisValidator implements Validator
{
    private JsonSchemaValidator $validator;

    public function __construct()
    {
        $this->validator = new JsonSchemaValidator();
        $this->validator->setMaxErrors(10);
    }

    public function validate(array $data, JsonSchemaInterface $schema): ?Errors
    {
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

        $schema = json_decode(
            json_encode(
                $schema->getSchema(),
                flags: JSON_THROW_ON_ERROR,
            ),
            flags: JSON_THROW_ON_ERROR
        );

        $result = $this->validator->validate($data, $schema);

        if ($result->isValid()) {
            return null;
        }

        return new Errors(
            ...$this->yieldErrors($result->error())
        );
    }

    /**
     * @return iterable<KnpJsonSchemaError>
     */
    private function yieldErrors(ValidationError $error): iterable
    {
        $formatter = new ErrorFormatter();

        $errors = $formatter->format($error);

        foreach ($errors as $field => $message) {
            $formatted = sizeof($message) === 1
                ? $message[0]
                : json_encode($message)
            ;

            yield new KnpJsonSchemaError(
                $field,
                $formatted,
            );
        }
    }
}
