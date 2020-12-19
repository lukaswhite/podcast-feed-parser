<?php


namespace Lukaswhite\PodcastFeedParser\Traits;

use Lukaswhite\PodcastFeedParser\Image;

trait HasImage
{
    /**
     * @var Image
     */
    protected $image;

    /**
     * @return Image
     */
    public function getImage(): ?Image
    {
        return $this->image;
    }

    /**
     * @param Image $image
     * @return self
     */
    public function setImage($image): self
    {
        $this->image = $image;
        return $this;
    }

}