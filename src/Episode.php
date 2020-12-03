<?php

namespace Lukaswhite\PodcastFeedParser;

use Lukaswhite\PodcastFeedParser\Traits\HasArtwork;
use Lukaswhite\PodcastFeedParser\Traits\HasDescription;
use Lukaswhite\PodcastFeedParser\Traits\HasExplicit;
use Lukaswhite\PodcastFeedParser\Traits\HasLink;
use Lukaswhite\PodcastFeedParser\Traits\HasTitles;

class Episode
{
    use     HasTitles
        ,   HasDescription
        ,   HasArtwork
        ,   HasLink
        ,   HasExplicit;

    /**
     * @var string
     */
    protected $guid;

    /**
     * @var Media
     */
    protected $media;

    /**
     * @var \DateTime
     */
    protected $publishedDate;

    /**
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * @param string $guid
     * @return Episode
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;
        return $this;
    }

    /**
     * @return Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param Media $media
     * @return Episode
     */
    public function setMedia($media)
    {
        $this->media = $media;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPublishedDate()
    {
        return $this->publishedDate;
    }

    /**
     * @param \DateTime $publishedDate
     * @return Episode
     */
    public function setPublishedDate($publishedDate)
    {
        $this->publishedDate = $publishedDate;
        return $this;
    }

}