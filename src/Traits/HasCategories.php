<?php


namespace Lukaswhite\PodcastFeedParser\Traits;

use Lukaswhite\PodcastFeedParser\Category;

/**
 * Trait HasCategories
 * @package Lukaswhite\PodcastFeedParser\Traits
 */
trait HasCategories
{
    /**
     * @var array
     */
    protected $categories = [];

    /**
     * @param string $type
     * @return array
     */
    public function getCategories(string $type = null)
    {
        if (!$type) {
            return $this->categories;
        }
        return array_values(array_filter(
            $this->categories,
            function(Category $category) use ($type){
                return $category->getType() === $type;
            }
        ));
    }

    /**
     * @param Category $category
     * @return $this
     */
    public function addCategory(Category $category): self
    {
        $this->categories[] = $category;
        return $this;
    }
}