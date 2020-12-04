<?php

namespace Lukaswhite\PodcastFeedParser;

class Link
{
    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $rel;

    /**
     * @var string
     */
    protected $type;

    /**
     * Link constructor.
     *
     * @param string $uri
     */
    public function __construct(string $uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getRel()
    {
        return $this->rel;
    }

    /**
     * @param string $rel
     * @return Link
     */
    public function setRel($rel)
    {
        $this->rel = $rel;
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
     * @return Link
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
}