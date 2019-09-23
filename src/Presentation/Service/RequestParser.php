<?php

declare(strict_types=1);

namespace App\Presentation\Service;

use DateTimeImmutable;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use function array_key_exists;
use function array_merge_recursive;
use function array_slice;
use function count;
use function explode;
use function implode;
use function is_array;
use function is_string;
use function json_decode;
use function mb_strtolower;

class RequestParser
{
    private const SEPARATOR = '.';

    private $request;
    private $content;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->content = $this->getData();
    }

    public function getFields(): array
    {
        return $this->getArray('fields');
    }

    public function getIncludes(): array
    {
        return $this->getArray('include');
    }

    public function getFilters(): array
    {
        return $this->getArray('filter');
    }

    public function getSorts(): array
    {
        return $this->getArray('sort');
    }

    public function getLimit(): int
    {
        return $this->getInt('page'.self::SEPARATOR.'limit');
    }

    public function getOffset(): int
    {
        return $this->getInt('page'.self::SEPARATOR.'offset');
    }

    public function getDate(string $key): ?DateTimeImmutable
    {
        try {
            return new DateTimeImmutable($this->getString($key));
        } catch (Exception $exception) {
            return null;
        }
    }

    public function getArray(string $key = null): array
    {
        return (array) $this->getItem($key, $this->content);
    }

    public function getBoolean(string $key): bool
    {
        $item = $this->getItem($key, $this->content);

        if (is_string($item) && 'false' === mb_strtolower($item)) {
            return false;
        }

        return (bool) $item;
    }

    public function getInt(string $key): int
    {
        return (int) $this->getIntOrNull($key);
    }

    public function getIntOrNull(string $key): ?int
    {
        $value = $this->getItem($key, $this->content);

        return null === $value ? null : (int) $value;
    }

    public function getFloat(string $key): float
    {
        return (float) $this->getFloatOrNull($key);
    }

    public function getFloatOrNull(string $key): ?float
    {
        $value = $this->getItem($key, $this->content);

        return null === $value ? null : (float) $value;
    }

    public function getString(string $key): string
    {
        return (string) $this->getStringOrNull($key);
    }

    public function getStringOrNull(string $key): ?string
    {
        $value = $this->getItem($key, $this->content);

        return null === $value ? null : (string) $value;
    }

    private function getData(): array
    {
        $query = $this->request->query->all();

        /** @see https://jsonapi.org/format/#fetching-includes */
        if (array_key_exists('include', $query)) {
            $query['include'] = explode(',', $query['include']);
        }

        /** @see https://jsonapi.org/format/#fetching-sparse-fieldsets */
        if (array_key_exists('fields', $query) && is_array($query['fields'])) {
            foreach ($query['fields'] as &$fields) {
                $fields = explode(',', $fields);
            }
        }

        /** @see https://jsonapi.org/format/#fetching-filtering */
        if (array_key_exists('filter', $query) && is_array($query['filter'])) {
            foreach ($query['filter'] as &$filters) {
                $filters = explode(',', $filters);
            }
        }

        /** @see https://jsonapi.org/format/#fetching-sorting */
        if (array_key_exists('sort', $query)) {
            $query['sort'] = explode(',', $query['sort']);
        }

        return array_merge_recursive(
            $query,
            $this->request->request->all(),
            $this->request->files->all(),
            (array) json_decode($this->request->getContent(), true),
            $this->request->attributes->all()['_route_params'],
        );
    }

    private function getItem(string $key = null, array $content = [])
    {
        if (null === $key) {
            return $content;
        }

        $keys = explode(self::SEPARATOR, $key);

        if (1 === count($keys)) {
            return array_key_exists($key, $content) ? $content[$key] : null;
        }

        if (!array_key_exists($keys[0], $content)) {
            return null;
        }

        return $this->getItem(
            implode(self::SEPARATOR, array_slice($keys, 1)),
            $content[$keys[0]]
        );
    }
}
