<?php


namespace Lukaswhite\PodcastFeedParser\Rawvoice;

/**
 * Class Subscribe
 *
 * @package Lukaswhite\PodcastFeedParser\Rawvoice
 */
class Subscribe
{
    const FEED = 'feed';
    const ITUNES = 'itunes';
    const GOOGLEPLAY = 'googleplay';
    const BLUBRRY = 'blubrry';
    const HTML = 'html';
    const STITCHER = 'stitcher';
    const TUNEIN = 'tunein';

    /**
     * @var array
     */
    protected $links = [];

    /**
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param string $platform
     * @return string
     */
    public function getLink(string $platform): ?string
    {
        return isset($this->links[$platform]) ? $this->links[$platform] : null;
    }

    /**
     * @param string $platform
     * @param string $link
     * @return self
     */
    public function addLink(string $platform, string $link): self
    {
        $this->links[$platform] = $link;
        return $this;
    }

}