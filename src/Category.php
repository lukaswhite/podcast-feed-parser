<?php

namespace Lukaswhite\PodcastFeedParser;

class Category extends \Lukaswhite\ItunesCategories\Category
{
    const   ITUNES          =   'itunes';
    const   GOOGLE_PLAY     =   'googleplay';

    /**
     * @var string
     */
    protected $type;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Category
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

}