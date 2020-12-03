<?php


namespace Lukaswhite\PodcastFeedParser\Traits;

use Lukaswhite\PodcastFeedParser\Artwork;

trait HasUri
{
    /**
     * @var string
     */
    protected $uri;

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * @return HasUri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

}