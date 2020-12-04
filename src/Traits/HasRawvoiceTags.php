<?php


namespace Lukaswhite\PodcastFeedParser\Traits;

use Lukaswhite\PodcastFeedParser\Rawvoice\Subscribe;

trait HasRawvoiceTags
{
    /**
     * @var string
     */
    protected $rawvoiceRating;

    /**
     * @var string
     */
    protected $rawvoiceLocation;

    /**
     * @var string
     */
    protected $rawvoiceFrequency;

    /**
     * @var Subscribe
     */
    protected $rawvoiceSubscribe;

    /**
     * @return string
     */
    public function getRawvoiceRating()
    {
        return $this->rawvoiceRating;
    }

    /**
     * @param string $rawvoiceRating
     * @return HasRawvoiceTags
     */
    public function setRawvoiceRating($rawvoiceRating)
    {
        $this->rawvoiceRating = $rawvoiceRating;
        return $this;
    }

    /**
     * @return string
     */
    public function getRawvoiceLocation()
    {
        return $this->rawvoiceLocation;
    }

    /**
     * @param string $rawvoiceLocation
     * @return HasRawvoiceTags
     */
    public function setRawvoiceLocation($rawvoiceLocation)
    {
        $this->rawvoiceLocation = $rawvoiceLocation;
        return $this;
    }

    /**
     * @return string
     */
    public function getRawvoiceFrequency()
    {
        return $this->rawvoiceFrequency;
    }

    /**
     * @param string $rawvoiceFrequency
     * @return HasRawvoiceTags
     */
    public function setRawvoiceFrequency($rawvoiceFrequency)
    {
        $this->rawvoiceFrequency = $rawvoiceFrequency;
        return $this;
    }

    /**
     * @return Subscribe
     */
    public function getRawvoiceSubscribe()
    {
        return $this->rawvoiceSubscribe;
    }

    /**
     * @param Subscribe $rawvoiceSubscribe
     * @return HasRawvoiceTags
     */
    public function setRawvoiceSubscribe($rawvoiceSubscribe)
    {
        $this->rawvoiceSubscribe = $rawvoiceSubscribe;
        return $this;
    }
}