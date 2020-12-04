<?php


namespace Lukaswhite\PodcastFeedParser\Traits;

use Lukaswhite\PodcastFeedParser\Artwork;

trait IsRssFeed
{
    /**
     * @var string
     */
    protected $generator;

    /**
     * @var \DateTime
     */
    protected $lastBuildDate;

    /**
     * @return string
     */
    public function getGenerator()
    {
        return $this->generator;
    }

    /**
     * @param string $generator
     * @return IsRssFeed
     */
    public function setGenerator($generator)
    {
        $this->generator = $generator;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastBuildDate()
    {
        return $this->lastBuildDate;
    }

    /**
     * @param \DateTime $lastBuildDate
     * @return IsRssFeed
     */
    public function setLastBuildDate($lastBuildDate)
    {
        $this->lastBuildDate = $lastBuildDate;
        return $this;
    }

}