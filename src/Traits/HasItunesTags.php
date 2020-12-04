<?php


namespace Lukaswhite\PodcastFeedParser\Traits;

use Lukaswhite\PodcastFeedParser\Owner;

trait HasItunesTags
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $newFeedUrl;

    /**
     * @var Owner
     */
    protected $owner;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isEpisodic()
    {
        return $this->type && $this->type === self::EPISODIC;
    }

    /**
     * @return bool
     */
    public function isSerial()
    {
        return $this->type && $this->type === self::SERIAL;
    }

    /**
     * @param string $type
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getNewFeedUrl()
    {
        return $this->newFeedUrl;
    }

    /**
     * @param string $newFeedUrl
     * @return HasItunesTags
     */
    public function setNewFeedUrl($newFeedUrl)
    {
        $this->newFeedUrl = $newFeedUrl;
        return $this;
    }

    /**
     * @return Owner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param Owner $owner
     * @return self
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
        return $this;
    }
}