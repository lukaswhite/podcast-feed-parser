<?php

namespace Lukaswhite\PodcastFeedParser;

class Category
{
    const   ITUNES          =   'itunes';
    const   GOOGLE_PLAY     =   'googleplay';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $subCategories = [];

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return Category
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return array
     */
    public function getSubCategories()
    {
        return $this->subCategories;
    }

    /**
     * @param Category $subCategory
     * @return Category
     */
    public function addSubCategory(Category $subCategory): Category
    {
        $this->subCategories[] = $subCategory;
        return $this;
    }

}