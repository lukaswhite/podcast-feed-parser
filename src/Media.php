<?php

namespace Lukaswhite\PodcastFeedParser;

use Lukaswhite\PodcastFeedParser\Traits\HasUri;

class Media
{
    use     HasUri;

    protected $length;

    protected $mimeType;

    /**
     * @return mixed
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param mixed $length
     * @return Media
     */
    public function setLength($length)
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param mixed $mimeType
     * @return Media
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
    }


}