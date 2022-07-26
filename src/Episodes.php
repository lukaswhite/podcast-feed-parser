<?php

namespace Lukaswhite\PodcastFeedParser;

/**
 * Class Episodes
 *
 * A simple container class for a podcast's episodes.
 *
 * @package Lukaswhite\PodcastFeedParser
 */
class Episodes implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * @var array
     */
    protected $items;

    /**
     * Episodes constructor.
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @param Episode $episode
     * @return $this
     */
    public function add(Episode $episode): self
    {
        $this->items[] = $episode;
        return $this;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * Get the first episode
     *
     * @return Episode|null
     */
    public function first(): ?Episode
    {
        if ( ! $this->count( ) ) {
            return null;
        }
        return $this->items[0];
    }

    /**
     * Get the most recent episode
     *
     * @return Episode|null
     */
    public function mostRecent(): ?Episode
    {
        $this->newestFirst();
        return $this->first();
    }

    /**
     * Get the last episode
     *
     * @return Episode|null
     */
    public function last(): ?Episode
    {
        if ( ! $this->count( ) ) {
            return null;
        }
        return $this->items[$this->count()-1];
    }

    /**
     * Find an episode by its GUID
     *
     * @param string $guid
     * @return Episode
     */
    public function findByGuid(string $guid): ?Episode
    {
        $episode = current(array_filter(
            $this->items,
            function(Episode $episode) use ($guid) {
                return $episode->getGuid() === $guid;
            }
        ));
        if ( $episode ) {
            return $episode;
        }
        return null;
    }

    /**
     * @return self
     */
    public function newestFirst(): self
    {
        usort(
            $this->items,
            function(Episode $a, Episode $b) {
                return $b->getPublishedDate()->getTimestamp() - $a->getPublishedDate()->getTimestamp();
            }
        );
        return $this;
    }

    /**
     * @return self
     */
    public function oldestFirst(): self
    {
        usort(
            $this->items,
            function(Episode $a, Episode $b) {
                return $a->getPublishedDate()->getTimestamp() - $b->getPublishedDate()->getTimestamp();
            }
        );
        return $this;
    }

    /**
     * @return self
     */
    public function sortByEpisodeNumber(): self
    {
        usort(
            $this->items,
            function(Episode $a, Episode $b) {
                return $a->getEpisodeNumber() - $b->getEpisodeNumber();
            }
        );
        return $this;
    }

    /**
     * @return array
     */
    public function getSeasons(): array
    {
        $seasons = [];
        foreach($this->items as $episode) {
            /** @ var Episode $episode */
            if ($episode->getSeason()) {
                if (!isset($seasons[$episode->getSeason()])) {
                    $seasons[$episode->getSeason()] = new Episodes();
                }
                $seasons[$episode->getSeason()]->add($episode);
            }
        }
        ksort($seasons);
        foreach($seasons as $season) {
            /** @var Episodes $season */
            $season->sortByEpisodeNumber();
        }
        return $seasons;
    }

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return bool true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0
     */
    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0
     */
    public function offsetGet($offset): mixed
    {
        return $this->items[$offset];
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0
     */
    public function offsetSet($offset, $value): void
    {
        // noop
    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0
     */
    public function offsetUnset($offset): void
    {
        // noop
    }
}