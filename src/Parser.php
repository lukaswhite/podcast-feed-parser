<?php

namespace Lukaswhite\PodcastFeedParser;

use Lukaswhite\PodcastFeedParser\Exceptions\FileNotFoundException;
use Lukaswhite\PodcastFeedParser\Exceptions\InvalidXmlException;
use Lukaswhite\PodcastFeedParser\Rawvoice\Subscribe;

/**
 * Class Parser
 *
 * Parse a podcast feed.
 *
 * @package Lukaswhite\PodcastFeedParser
 */
class Parser
{
    /**
     * Class constants for the various namespaces
     */
    const NS_ITUNES = 'http://www.itunes.com/dtds/podcast-1.0.dtd';
    const NS_GOOGLE_PLAY = 'http://www.google.com/schemas/play-podcasts/1.0';
    const NS_ATOM = 'http://www.w3.org/2005/Atom';
    const NS_SYNDICATION = 'http://purl.org/rss/1.0/modules/syndication/';
    const NS_RAWVOICE = 'http://www.rawvoice.com/rawvoiceRssModule/';

    /**
     * The raw feed content
     *
     * @var string
     */
    protected $content;

    /**
     * @var \SimplePie
     */
    protected $sp;

    /**
     * @param string $content
     * @return $this
     * @throws InvalidXmlException
     */
    public function setContent(string $content): self
    {
        try {
            simplexml_load_string($content);
        } catch (\Exception $e) {
            throw new InvalidXmlException('The feed does not appear to be valid XML');
        }

        $this->content = $content;
        return $this;
    }

    /**
     * @param string $filepath
     * @return self
     * @throws FileNotFoundException
     * @throws InvalidXmlException
     */
    public function load(string $filepath): self
    {
        if (!file_exists($filepath)) {
            throw new FileNotFoundException('The file could not be found');
        }
        $this->setContent(file_get_contents($filepath));
        return $this;
    }

    /**
     * Run the parser and return an object that represents the parsed podcast.
     *
     * @return Podcast
     * @throws \Exception
     */
    public function run(): Podcast
    {
        $this->sp = new \SimplePie();
        $this->sp->set_raw_data($this->content);
        $this->sp->init();

        $podcast = new Podcast();
        $podcast->setTitle($this->sp->get_title())
            ->setDescription($this->sp->get_description())
            ->setLanguage($this->sp->get_language())
            ->setCopyright($this->sp->get_copyright())
            ->setLink($this->sp->get_link());

        $this->parseRssTags($podcast);
        $this->parseAtomTags($podcast);
        $this->parseSyndicationFields($podcast);
        $this->parseRawvoiceFields($podcast);

        if ($this->sp->get_author()) {
            $podcast->setAuthor($this->sp->get_author()->get_name());
        }

        $iTunesType = $this->sp->get_channel_tags(self::NS_ITUNES, 'type');
        if ($iTunesType && count($iTunesType)) {
            $podcast->setType($iTunesType[0]['data']);
        }

        $editor = $this->sp->get_channel_tags('', 'managingEditor');
        if ($editor && count($editor)) {
            $podcast->setManagingEditor($editor[0]['data']);
        }

        if ( $this->getSingleNamespacedChannelItem(self::NS_ITUNES, 'subtitle')) {
            $podcast->setSubtitle(
                $this->getSingleNamespacedChannelItem(self::NS_ITUNES, 'subtitle')['data']
            );
        }

        if ( $this->getSingleNamespacedChannelItem(self::NS_ITUNES, 'explicit')) {
            $podcast->setExplicit(
                $this->getSingleNamespacedChannelItem(self::NS_ITUNES, 'explicit')['data']
            );
        }

        if ( $this->getSingleNamespacedChannelItem(self::NS_ITUNES, 'new-feed-url')) {
            $podcast->setNewFeedUrl(
                $this->getSingleNamespacedChannelItem(self::NS_ITUNES, 'new-feed-url')['data']
            );
        }

        $image = $this->getSingleNamespacedChannelItem(self::NS_ITUNES, 'image');
        if ( $image ) {
            $artwork = new Artwork();
            $artwork->setUri(
                $this->getSingleNamespacedChannelItem(self::NS_ITUNES, 'image')['attribs']['']['href']
            );
            $podcast->setArtwork($artwork);
        }

        if ( $this->getSingleNamespacedChannelItem(self::NS_ITUNES, 'owner')) {
            $ownerData = $this->getSingleNamespacedChannelItem(self::NS_ITUNES, 'owner');
            $owner = new Owner();
            if (isset($ownerData['child'])&&
                isset($ownerData['child'][self::NS_ITUNES])&&
                isset($ownerData['child'][self::NS_ITUNES]['name'])) {
                $owner->setName($ownerData['child'][self::NS_ITUNES]['name'][0]['data']);
            }
            if (isset($ownerData['child'])&&
                isset($ownerData['child'][self::NS_ITUNES])&&
                isset($ownerData['child'][self::NS_ITUNES]['email'])) {
                $owner->setEmail($ownerData['child'][self::NS_ITUNES]['email'][0]['data']);
            }
            $podcast->setOwner($owner);
        }

        $itunesCategories = $this->sp->get_channel_tags(self::NS_ITUNES, 'category');
        if ($itunesCategories && count($itunesCategories)) {
            foreach($itunesCategories as $categoryData) {
                $category = new Category();
                $category->setType(Category::ITUNES)
                    ->setName($categoryData['attribs']['']['text']);
                if(isset($categoryData['child'])&&is_array($categoryData['child'])) {
                    foreach($categoryData['child'][self::NS_ITUNES]['category'] as $subCategoryData) {
                        $category->addSubCategory(
                            ( new Category() )
                                ->setType(Category::ITUNES)
                                ->setName($subCategoryData['attribs']['']['text'])
                        );
                    }
                }
                $podcast->addCategory($category);
            }
        }

        $googlePlayCategories = $this->sp->get_channel_tags(self::NS_GOOGLE_PLAY, 'category');
        if ($googlePlayCategories && count($googlePlayCategories)) {
            foreach($googlePlayCategories as $categoryData) {
                $category = new Category();
                $category->setType(Category::GOOGLE_PLAY)
                    ->setName($categoryData['attribs']['']['text']);
                $podcast->addCategory($category);
            }
        }

        // Now add the episodes
        foreach ($this->sp->get_items() as $item) {
            $podcast->addEpisode($this->parseEpisodeItem($item));
        }

        return $podcast;
    }

    /**
     * @param Podcast $podcast
     * @throws \Exception
     */
    protected function parseRssTags(Podcast $podcast)
    {
        $generator = $this->sp->get_channel_tags('', 'generator');
        if ($generator && count($generator)) {
            $podcast->setGenerator($generator[0]['data']);
        }

        $lastBuildDate = $this->sp->get_channel_tags('', 'lastBuildDate');
        if ($lastBuildDate && count($lastBuildDate)) {
            $podcast->setLastBuildDate((new \DateTime())->setTimestamp(strtotime($lastBuildDate[0]['data'])));
        }
    }

    /**
     * @param Podcast $podcast
     */
    protected function parseAtomTags(Podcast $podcast)
    {
        $atomLinks = $this->sp->get_channel_tags(self::NS_ATOM, 'link');
        if($atomLinks && count($atomLinks)) {
            foreach ($atomLinks as $atomLink) {
                $link = new Link($atomLink['attribs']['']['href']);
                if (isset($atomLink['attribs']['']['rel'])) {
                    $link->setRel($atomLink['attribs']['']['rel']);
                }
                if (isset($atomLink['attribs']['']['type'])) {
                    $link->setType($atomLink['attribs']['']['type']);
                }
                $podcast->addAtomLink($link);
            }
        }
    }

    /**
     * @param Podcast $podcast
     * @throws \Exception
     */
    protected function parseSyndicationFields(Podcast $podcast)
    {
        $updatePeriod = $this->sp->get_channel_tags(self::NS_SYNDICATION, 'updatePeriod');
        if($updatePeriod&&count($updatePeriod)) {
            $podcast->setUpdatePeriod($updatePeriod[0]['data']);
        }
        $updateFrequency = $this->sp->get_channel_tags(self::NS_SYNDICATION, 'updateFrequency');
        if($updateFrequency&&count($updateFrequency)) {
            $podcast->setUpdateFrequency(intval($updateFrequency[0]['data']));
        }
        $updateBase = $this->sp->get_channel_tags(self::NS_SYNDICATION, 'updateBase');
        if($updateBase&&count($updateBase)) {
            $podcast->setUpdateBase((new \DateTime())->setTimestamp(strtotime($updateBase[0]['data'])));
        }
    }

    /**
     * @param Podcast $podcast
     * @throws \Exception
     */
    protected function parseRawvoiceFields(Podcast $podcast)
    {
        $rating = $this->sp->get_channel_tags(self::NS_RAWVOICE, 'rating');
        if($rating&&count($rating)) {
            $podcast->setRawvoiceRating($rating[0]['data']);
        }
        $location = $this->sp->get_channel_tags(self::NS_RAWVOICE, 'location');
        if($location&&count($location)) {
            $podcast->setRawvoiceLocation($location[0]['data']);
        }
        $frequency = $this->sp->get_channel_tags(self::NS_RAWVOICE, 'frequency');
        if($frequency&&count($frequency)) {
            $podcast->setRawvoiceFrequency($frequency[0]['data']);
        }
        $subscribe = $this->sp->get_channel_tags(self::NS_RAWVOICE, 'subscribe');
        if($subscribe&&count($subscribe)) {
            $links = new Subscribe();
            foreach($subscribe[0]['attribs'][''] as $platform => $link) {
                $links->addLink($platform,$link);
            }
            $podcast->setRawvoiceSubscribe($links);
        }
    }

    /**
     * @param \SimplePie_Item $item
     * @return Episode
     * @throws \Exception
     */
    protected function parseEpisodeItem(\SimplePie_Item $item)
    {
        $episode = new Episode();
        $episode->setTitle($item->get_title())
            ->setDescription($item->get_description())
            ->setLink($item->get_link())
            ->setPublishedDate(new \DateTime($item->get_date()));

        $guid = $item->get_item_tags('', 'guid');
        if ($guid && count($guid)) {
            $episode->setGuid($guid[0]['data']);
        }

        $subtitle = $item->get_item_tags(self::NS_ITUNES, 'subtitle');
        if ($subtitle && count($subtitle)) {
            $episode->setSubtitle($subtitle[0]['data']);
        }

        $explicit = $item->get_item_tags(self::NS_ITUNES, 'explicit');
        if ( $explicit && count($explicit)) {
            $episode->setExplicit($explicit[0]['data']);
        }

        $episodeNumber = $item->get_item_tags(self::NS_ITUNES, 'episode');
        if ( $episodeNumber && count($episodeNumber)) {
            $episode->setEpisodeNumber(intval($episodeNumber[0]['data']));
        }

        $season = $item->get_item_tags(self::NS_ITUNES, 'season');
        if ( $season && count($season)) {
            $episode->setSeason(intval($season[0]['data']));
        }

        $episodeType = $item->get_item_tags(self::NS_ITUNES, 'episodeType');
        if ( $episodeType && count($episodeType)) {
            $episode->setType($episodeType[0]['data']);
        }

        $duration = $item->get_item_tags(self::NS_ITUNES, 'duration');
        if ( $duration && count($duration)) {
            $episode->setDuration($duration[0]['data']);
        }

        $image = $item->get_item_tags(self::NS_ITUNES, 'image');
        if ( $image && count($image)) {
            $artwork = new Artwork();
            $artwork->setUri(
                $image[0]['attribs']['']['href']
            );
            $episode->setArtwork($artwork);
        }

        $enclosure = $item->get_enclosure();
        if ( $enclosure && $enclosure->get_link()) {
            $media = new Media();
            $media->setUri($enclosure->get_link())
                ->setMimeType($enclosure->get_type())
                ->setLength($enclosure->get_length());

            $episode->setMedia($this->getFile($item));
        }

        return $episode;
    }

    /**
     * @param \SimplePie_Item $item
     * @return Media
     */
    protected function getFile(\SimplePie_Item $item): Media
    {
        $enclosure = $item->get_enclosure();
        $media = new Media();
        $media->setUri($enclosure->get_link())
            ->setMimeType($enclosure->get_type())
            ->setLength($enclosure->get_length());
        return $media;
    }

    /**
     * @param $namespace
     * @param $name
     * @param null $item
     * @return mixed
     */
    protected function getSingleNamespacedChannelItem($namespace, $name, $item = null )
    {
        $items = $this->sp->get_channel_tags($namespace, $name);
        if ( $items && count( $items ) ) {
            return $items[0];
        }
    }


}