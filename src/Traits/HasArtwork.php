<?php


namespace Lukaswhite\PodcastFeedParser\Traits;

use Lukaswhite\PodcastFeedParser\Artwork;

trait HasArtwork
{
    /**
     * @var Artwork
     */
    protected $artwork;

    /**
     * @return Artwork
     */
    public function getArtwork()
    {
        return $this->artwork;
    }

    /**
     * @param Artwork $artwork
     * @return HasArtwork
     */
    public function setArtwork($artwork)
    {
        $this->artwork = $artwork;
        return $this;
    }

}