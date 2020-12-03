<?php


namespace Lukaswhite\PodcastFeedParser\Traits;

trait HasExplicit
{
    /**
     * @var string
     */
    protected $explicit;

    /**
     * @return string
     */
    public function getExplicit()
    {
        return $this->explicit;
    }

    /**
     * @param string $explicit
     * @return HasExplicit
     */
    public function setExplicit($explicit)
    {
        $this->explicit = $explicit;
        return $this;
    }

}