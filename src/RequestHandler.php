<?php

declare(strict_types=1);

namespace KnpLabs\JsonSchemaBundle;

use KnpLabs\JsonSchema\Collection;
use KnpLabs\JsonSchema\JsonSchemaInterface;
use KnpLabs\JsonSchema\Validator;
use KnpLabs\JsonSchemaBundle\Exception\JsonSchemaException;
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
        'application/x-www-form-urlencoded',
    ];

    private Collection $schemas;
    private Validator $validator;

    public function __construct(
        Collection $schemas,
        Validator $validator
    ) {
        $this->schemas = $schemas;
        $this->validator = $validator;
    }

    /**
     * @template T
     *
     * @param class-string<JsonSchemaInterface<T>> $schemaClass
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

        /** @var T */
        $data = match ($mediaType) {
            'application/x-www-form-urlencoded' => $request->request->all(),
            default => json_decode((string) $request->getContent(false), true, flags: JSON_THROW_ON_ERROR),
        };

        $errors = $this->validator->validate($data, $this->schemas->get($schemaClass));

        if (null !== $errors) {
            throw new JsonSchemaException($errors);
        }

        return $data;
    }
}
