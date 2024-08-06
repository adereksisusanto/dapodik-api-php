<?php

namespace Adereksisusanto\DapodikAPI;

use Adereksisusanto\DapodikAPI\Interfaces\CollectionInterface;

use function array_filter;

use const ARRAY_FILTER_USE_BOTH;

use function array_key_exists;
use function array_keys;
use function array_map;
use function array_reverse;
use function array_search;
use function array_slice;
use function array_values;

use ArrayIterator;
use Closure;

use function count;
use function current;

use Doctrine\Common\Collections\Collection as DoctrineCollection;
use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use Doctrine\Common\Collections\Expr\ClosureExpressionVisitor;
use Doctrine\Common\Collections\Selectable as DoctrineSelectable;

use function end;
use function in_array;
use function key;
use function next;
use function reset;

use ReturnTypeWillChange;

use function spl_object_hash;
use function uasort;

class Collection implements DoctrineCollection, DoctrineSelectable, CollectionInterface
{
    protected array $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function first()
    {
        return reset($this->items);
    }

    protected function createFrom(array $items): Collection
    {
        return new static($items);
    }

    public function last()
    {
        return end($this->items);
    }

    public function key()
    {
        return key($this->items);
    }

    public function next()
    {
        return next($this->items);
    }

    public function current()
    {
        return current($this->items);
    }

    public function remove($key)
    {
        if (! isset($this->items[$key]) && ! array_key_exists($key, $this->items)) {
            return null;
        }

        $removed = $this->items[$key];
        unset($this->items[$key]);

        return $removed;
    }

    public function removeElement($element): bool
    {
        $key = array_search($element, $this->items, true);

        if ($key === false) {
            return false;
        }

        unset($this->items[$key]);

        return true;
    }

    #[ReturnTypeWillChange]
    public function offsetExists($offset): bool
    {
        return $this->containsKey($offset);
    }

    #[ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    #[ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        if (! isset($offset)) {
            $this->add($value);

            return;
        }

        $this->set($offset, $value);
    }

    #[ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    public function containsKey($key): bool
    {
        return isset($this->items[$key]) || array_key_exists($key, $this->items);
    }

    public function contains($element): bool
    {
        return in_array($element, $this->items, true);
    }

    public function exists(Closure $p): bool
    {
        foreach ($this->items as $key => $element) {
            if ($p($key, $element)) {
                return true;
            }
        }

        return false;
    }

    public function indexOf($element)
    {
        return array_search($element, $this->items, true);
    }

    public function get($key)
    {
        return $this->items[$key] ?? null;
    }

    public function getKeys(): array
    {
        return array_keys($this->items);
    }

    public function getValues(): array
    {
        return array_values($this->items);
    }

    #[ReturnTypeWillChange]
    public function count(): int
    {
        return count($this->items);
    }

    public function set($key, $value)
    {
        $this->items[$key] = $value;
    }

    public function add($element): bool
    {
        $this->items[] = $element;

        return true;
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    #[ReturnTypeWillChange]
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function map(Closure $func)
    {
        return $this->createFrom(array_map($func, $this->items));
    }

    public function filter(Closure $p)
    {
        return $this->createFrom(array_filter($this->items, $p, ARRAY_FILTER_USE_BOTH));
    }

    public function forAll(Closure $p): bool
    {
        foreach ($this->items as $key => $element) {
            if (! $p($key, $element)) {
                return false;
            }
        }

        return true;
    }

    public function partition(Closure $p): array
    {
        $matches = $noMatches = [];

        foreach ($this->items as $key => $element) {
            if ($p($key, $element)) {
                $matches[$key] = $element;
            } else {
                $noMatches[$key] = $element;
            }
        }

        return [$this->createFrom($matches), $this->createFrom($noMatches)];
    }

    public function __toString()
    {
        return self::class . '@' . spl_object_hash($this);
    }

    public function clear()
    {
        $this->items = [];
    }

    public function slice($offset, $length = null): array
    {
        return array_slice($this->items, $offset, $length, true);
    }

    public function matching(DoctrineCriteria $criteria)
    {
        $expr = $criteria->getWhereExpression();
        $filtered = $this->items;

        if ($expr) {
            $visitor = new ClosureExpressionVisitor();
            $filter = $visitor->dispatch($expr);
            $filtered = array_filter($filtered, $filter);
        }

        $orderings = $criteria->getOrderings();

        if ($orderings) {
            $next = null;
            foreach (array_reverse($orderings) as $field => $ordering) {
                $next = ClosureExpressionVisitor::sortByField($field, $ordering === DoctrineCriteria::DESC ? -1 : 1, $next);
            }

            uasort($filtered, $next);
        }

        $offset = $criteria->getFirstResult();
        $length = $criteria->getMaxResults();

        if ($offset || $length) {
            $filtered = array_slice($filtered, (int) $offset, $length);
        }

        return $this->createFrom($filtered);
    }

    /**
     * @param int $flags [optional] <p>
     * Bitmask consisting of <b>JSON_HEX_QUOT</b>,
     * <b>JSON_HEX_TAG</b>,
     * <b>JSON_HEX_AMP</b>,
     * <b>JSON_HEX_APOS</b>,
     * <b>JSON_NUMERIC_CHECK</b>,
     * <b>JSON_PRETTY_PRINT</b>,
     * <b>JSON_UNESCAPED_SLASHES</b>,
     * <b>JSON_FORCE_OBJECT</b>,
     * <b>JSON_UNESCAPED_UNICODE</b>.
     * <b>JSON_THROW_ON_ERROR</b> The behaviour of these
     * constants is described on
     * the JSON constants page.
     * </p>
     * @param int $depth [optional] <p>
     * Set the maximum depth. Must be greater than zero.
     * </p>
     * @return string|false a JSON encoded string on success or <b>FALSE</b> on failure.
     */
    public function toJson(int $flags = 0, int $depth = 512): string
    {
        return json_encode($this->items, $flags, $depth);
    }
}
