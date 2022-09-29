<?php

declare(strict_types=1);

namespace Knp\JsonSchemaBundle;

use Knp\JsonSchema\Collection;
use Knp\JsonSchema\Validator;
use Knp\JsonSchemaBundle\Exception\JsonSchemaException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class RequestHandler
{
    /**
     * @var string[]
     */
    private const CONTENT_TYPES = [
        'application/json',
        'application/json;charset=utf-8',
    ];

    public function __construct(
        private readonly Collection $schemas,
        private readonly Validator $validator
    ) {
    }

    /**
     * @template T
     *
     * @param class-string<JsonSchema<T>> $schemaClass
     *
     * @return T
     */
    public function extractJson(Request $request, string $schemaClass)
    {
        $mediaType = $request->headers->get('content-type');

        if (\is_string($mediaType)) {
            $mediaType = mb_strtolower($mediaType);
        }

        if (false === \in_array($mediaType, self::CONTENT_TYPES, true)) {
            throw new UnsupportedMediaTypeHttpException(
                headers: ['Accept' => self::CONTENT_TYPES]
            );
        }

        /** @var string */
        $content = $request->getContent(false);

        /** @var T */
        $data = json_decode($content, true, flags: JSON_THROW_ON_ERROR);

        $errors = $this->validator->validate($data, $this->schemas->get($schemaClass));

        if (null !== $errors) {
            throw new JsonSchemaException($errors);
        }

        return $data;
    }
}
