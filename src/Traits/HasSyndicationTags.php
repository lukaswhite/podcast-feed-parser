<?php


namespace Lukaswhite\PodcastFeedParser\Traits;

trait HasSyndicationTags
{
    /**
     * @var string
     */
    protected $updatePeriod;

    /**
     * @var int
     */
    protected $updateFrequency;

    /**
     * @var \DateTime
     */
    protected $updateBase;

    /**
     * @return string
     */
    public function getUpdatePeriod()
    {
        return $this->updatePeriod;
    }

    /**
     * @param string $updatePeriod
     * @return HasSyndicationTags
     */
    public function setUpdatePeriod($updatePeriod)
    {
        $this->updatePeriod = $updatePeriod;
        return $this;
    }

    /**
     * @return int
     */
    public function getUpdateFrequency()
    {
        return $this->updateFrequency;
    }

    /**
     * @param int $updateFrequency
     * @return HasSyndicationTags
     */
    public function setUpdateFrequency($updateFrequency)
    {
        $this->updateFrequency = $updateFrequency;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdateBase()
    {
        return $this->updateBase;
    }

    /**
     * @param \DateTime $updateBase
     * @return self
     */
    public function setUpdateBase(\DateTime $updateBase)
    {
        $this->updateBase = $updateBase;
        return $this;
    }

}