<?php


namespace Lukaswhite\PodcastFeedParser\Traits;

trait HasLink
{
    /**
     * @var string
     */
    protected $link;

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return self
     */
    public function setLink($link): self
    {
        $this->link = $link;
        return $this;
    }
}