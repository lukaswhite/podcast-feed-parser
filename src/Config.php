<?php

namespace Lukaswhite\PodcastFeedParser;

use phpDocumentor\Reflection\Types\Boolean;

class Config
{
    /**
     * @var bool
     */
    protected $defaultToToday = true;

    /**
     * @var bool
     */
    protected $descriptionOnly = false;

    /**
     * By default, if there is no description field, then it will use SimplePie's behaviour,
     * which is to look in other fields such as the subtitle. Somteimes, for example for
     * feed validation, you may with to force the parser to only look in the description
     * field.
     *
     * @param bool $descriptionOnly
     */
    public function descriptionOnly($descriptionOnly = true)
    {
        $this->descriptionOnly = $descriptionOnly;
    }

    /*
     * By default, if no published date is present then it will use today's date. You can disable
     * that here.
     *
     * @return void
     */
    public function dontDefaultToToday()
    {
        $this->defaultToToday = false;
    }

    public function checkDescriptionOnly(): bool
    {
        return $this->descriptionOnly;
    }

    public function shouldDefaultToToday()
    {
        return $this->defaultToToday;
    }
}