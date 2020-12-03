<?php

namespace Lukaswhite\PodcastFeedParser;

use Lukaswhite\PodcastFeedParser\Contracts\HasArtwork;
use Lukaswhite\PodcastFeedParser\Exceptions\FileNotFoundException;
use Lukaswhite\PodcastFeedParser\Exceptions\InvalidXmlException;

class Parser
{
    const NS_ITUNES = 'http://www.itunes.com/dtds/podcast-1.0.dtd';
    const NS_GOOGLE_PLAY = 'http://www.google.com/schemas/play-podcasts/1.0';

    /**
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
     * @return Podcast
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

        if ($this->sp->get_author()) {
            $podcast->setAuthor($this->sp->get_author()->get_name());
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

        foreach ($this->sp->get_items() as $item) {
            $podcast->addEpisode($this->parseEpisodeItem($item));
        }

        return $podcast;
    }

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

    protected function getFile(\SimplePie_Item $item): Media
    {
        $enclosure = $item->get_enclosure();
        $media = new Media();
        $media->setUri($enclosure->get_link())
            ->setMimeType($enclosure->get_type())
            ->setLength($enclosure->get_length());
        return $media;
    }

    protected function getSingleNamespacedChannelItem($namespace, $name, $item = null )
    {
        $items = $this->sp->get_channel_tags($namespace, $name);
        if ( $items && count( $items ) ) {
            return $items[0];
        }
    }


}