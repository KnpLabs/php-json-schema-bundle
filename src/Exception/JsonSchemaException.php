<?php

declare(strict_types=1);

namespace Knp\JsonSchemaBundle\Exception;

use Knp\JsonSchema\Validator\Errors;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class JsonSchemaException extends BadRequestHttpException
{
    public function __construct(Errors $errors)
    {
        $data = [];

        foreach ($errors as $error) {
            $data[$error->getPath()][] = $error->getMessage();
        }

        parent::__construct(json_encode($data, JSON_THROW_ON_ERROR));
    }
}
