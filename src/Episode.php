<?php

namespace Lukaswhite\PodcastFeedParser;

use Lukaswhite\PodcastFeedParser\Traits\HasArtwork;
use Lukaswhite\PodcastFeedParser\Traits\HasAuthor;
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
        ,   HasAuthor
        ,   HasExplicit;

    /**
     * @var string
     */
    protected $guid;

    /**
     * @var bool
     */
    protected $guidIsPermalink = false;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $episodeNumber;

    /**
     * @var int
     */
    protected $season;

    /**
     * @var string
     */
    protected $duration;

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
     * @return bool
     */
    public function guidIsPermalink()
    {
        return $this->guidIsPermalink;
    }

    /**
     * @param bool $guidIsPermalink
     * @return Episode
     */
    public function setGuidIsPermalink(bool $guidIsPermalink)
    {
        $this->guidIsPermalink = $guidIsPermalink;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Episode
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getEpisodeNumber()
    {
        return $this->episodeNumber;
    }

    /**
     * @param int $episodeNumber
     * @return Episode
     */
    public function setEpisodeNumber($episodeNumber)
    {
        $this->episodeNumber = $episodeNumber;
        return $this;
    }

    /**
     * @return int
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * @param int $season
     * @return Episode
     */
    public function setSeason($season)
    {
        $this->season = $season;
        return $this;
    }

    /**
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param string $duration
     * @return Episode
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
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