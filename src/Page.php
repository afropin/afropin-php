<?php

namespace AfroPin;

/**
 * One page of a list. Iterate it for the records; $paging has page, pages, per_page and total (null when you asked for
 * page=all). Ask for the next page with ['page' => $page->paging['page'] + 1].
 */
final class Page implements \IteratorAggregate, \Countable
{
    public function __construct(public array $data, public ?array $paging = null)
    {
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->data);
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function hasMore(): bool
    {
        return $this->paging !== null && $this->paging['page'] < $this->paging['pages'];
    }
}
