<?php


namespace Lukaswhite\PodcastFeedParser\Traits;

use Lukaswhite\PodcastFeedParser\Link;

trait HasAtomTags
{
    /**
     * @var array
     */
    protected $atomLinks = [];

    /**
     * @return array
     */
    public function getAtomLinks()
    {
        return $this->atomLinks;
    }

    /**
     * @param Link $link
     * @return self
     */
    public function addAtomLink(Link $link)
    {
        $this->atomLinks[] = $link;
        return $this;
    }

}