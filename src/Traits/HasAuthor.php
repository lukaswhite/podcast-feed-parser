<?php


namespace Lukaswhite\PodcastFeedParser\Traits;

trait HasAuthor
{
    /**
     * @var string
     */
    protected $author;

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     * @return self
     */
    public function setAuthor($author): self
    {
        $this->author = $author;
        return $this;
    }
}