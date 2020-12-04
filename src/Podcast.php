<?php

namespace Lukaswhite\PodcastFeedParser;

use Lukaswhite\PodcastFeedParser\Traits\HasArtwork;
use Lukaswhite\PodcastFeedParser\Traits\HasAtomTags;
use Lukaswhite\PodcastFeedParser\Traits\HasCategories;
use Lukaswhite\PodcastFeedParser\Traits\HasDescription;
use Lukaswhite\PodcastFeedParser\Traits\HasExplicit;
use Lukaswhite\PodcastFeedParser\Traits\HasItunesTags;
use Lukaswhite\PodcastFeedParser\Traits\HasLink;
use Lukaswhite\PodcastFeedParser\Traits\HasRawvoiceTags;
use Lukaswhite\PodcastFeedParser\Traits\HasSyndicationTags;
use Lukaswhite\PodcastFeedParser\Traits\HasTitles;
use Lukaswhite\PodcastFeedParser\Traits\IsRssFeed;

class Podcast implements \Lukaswhite\PodcastFeedParser\Contracts\HasArtwork
{
    use     HasTitles
        ,   HasDescription
        ,   IsRssFeed
        ,   HasItunesTags
        ,   HasAtomTags
        ,   HasSyndicationTags
        ,   HasRawvoiceTags
        ,   HasArtwork
        ,   HasLink
        ,   HasExplicit
        ,   HasCategories;

    const EPISODIC = 'episodic';
    const SERIAL = 'serial';

    /**
     * @var array
     */
    protected $episodes = [];

    /**
     * @var string
     */
    protected $language;

    /**
     * @var string
     */
    protected $author;

    /**
     * @var string
     */
    protected $managingEditor;

    /**
     * @var string
     */
    protected $copyright;

    /**
     * @return array
     */
    public function getEpisodes()
    {
        return $this->episodes;
    }

    /**
     * @param Episode $episode
     * @return $this
     */
    public function addEpisode(Episode $episode)
    {
        $this->episodes[] = $episode;
        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     * @return Podcast
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     * @return Podcast
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getManagingEditor()
    {
        return $this->managingEditor;
    }

    /**
     * @param string $managingEditor
     * @return Podcast
     */
    public function setManagingEditor($managingEditor)
    {
        $this->managingEditor = $managingEditor;
        return $this;
    }

    /**
     * @return string
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     * @param string $copyright
     * @return Podcast
     */
    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;
        return $this;
    }

}