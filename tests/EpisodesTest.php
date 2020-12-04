<?php


class EpisodesTest extends \PHPUnit\Framework\TestCase
{
    public function test_is_countable()
    {
        $episodes = $this->getEpisodes();
        $this->assertEquals(3, count($episodes));
    }

    public function test_is_iterable()
    {
        $episodes = new \Lukaswhite\PodcastFeedParser\Episodes();
        $this->assertInstanceOf(ArrayIterator::class,$episodes->getIterator());
    }

    public function test_can_access_as_array()
    {
        $episodes = $this->getEpisodes();
        $this->assertTrue(isset($episodes[1]));
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Episode::class,$episodes[1]);
    }

    public function test_cannot_unset_items()
    {
        $episodes = $this->getEpisodes();
        unset($episodes[1]);
        $this->assertEquals(3, count($episodes));
    }

    public function test_cannot_replace_items()
    {
        $episodes = $this->getEpisodes();
        $this->assertEquals('Episode Two',$episodes[1]->getTitle());
        $episodes[1] = (new \Lukaswhite\PodcastFeedParser\Episode())->setTitle('Episode Twelve');
        $this->assertEquals('Episode Two',$episodes[1]->getTitle());
    }

    public function test_can_get_first_episode()
    {
        $episodes = $this->getEpisodes();
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Episode::class,$episodes->first());
        $this->assertEquals('Episode One',$episodes->first()->getTitle());
    }

    public function test_first_returns_null_if_empty()
    {
        $episodes = new \Lukaswhite\PodcastFeedParser\Episodes();
        $this->assertNull($episodes->first());
    }

    public function test_last_returns_null_if_empty()
    {
        $episodes = new \Lukaswhite\PodcastFeedParser\Episodes();
        $this->assertNull($episodes->last());
    }

    public function test_can_get_last_episode()
    {
        $episodes = $this->getEpisodes();
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Episode::class,$episodes->last());
        $this->assertEquals('Episode Three',$episodes->last()->getTitle());
    }

    public function test_can_find_episode_by_guid()
    {
        $episodes = $this->getEpisodes();
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Episode::class,$episodes->findByGuid('two'));
        $this->assertEquals('Episode Two',$episodes->findByGuid('two')->getTitle());
    }

    public function test_find_episode_by_guid_returns_null_if_not_found()
    {
        $episodes = $this->getEpisodes();
        $this->assertNull($episodes->findByGuid('xyz'));
    }

    public function test_can_sort_by_episode_number()
    {
        $episodes = $this->getEpisodesWithEpisodeNumbers();
        $episodes->sortByEpisodeNumber();
        $this->assertEquals('Episode One',$episodes->first()->getTitle());
        $this->assertEquals('Episode Four',$episodes->last()->getTitle());
    }

    public function test_can_split_into_seasons()
    {
        $episodes = $this->getEpisodesInSeasons();
        $seasons = $episodes->getSeasons();
        $this->assertTrue(is_array($seasons));
        $this->assertEquals(3,count($seasons));
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Episodes::class,$seasons[1]);
        $this->assertEquals(4,$seasons[1]->count());
        $this->assertEquals('s01e01',$seasons[1]->first()->getGuid());
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Episodes::class,$seasons[2]);
        $this->assertEquals(3,$seasons[2]->count());
        $this->assertEquals('s02e01',$seasons[2]->first()->getGuid());
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Episodes::class,$seasons[3]);
        $this->assertEquals(1,$seasons[3]->count());
        $this->assertEquals('s03e01',$seasons[3]->first()->getGuid());
    }



    protected function getEpisodes()
    {
        $episodes = new \Lukaswhite\PodcastFeedParser\Episodes();
        $episode = new \Lukaswhite\PodcastFeedParser\Episode();
        $episode->setTitle('Episode One');

        $episodes
            ->add(
                (new \Lukaswhite\PodcastFeedParser\Episode())
                    ->setTitle('Episode One')
                    ->setGuid('one')
            )
            ->add(
                (new \Lukaswhite\PodcastFeedParser\Episode())
                    ->setTitle('Episode Two')
                    ->setGuid('two')
            )
            ->add(
                (new \Lukaswhite\PodcastFeedParser\Episode())
                    ->setTitle('Episode Three')
                    ->setGuid('three')
            );
        return $episodes;
    }

    protected function getEpisodesInSeasons()
    {
        $episodes = new \Lukaswhite\PodcastFeedParser\Episodes();
        $episode = new \Lukaswhite\PodcastFeedParser\Episode();
        $episode->setTitle('Episode One');

        $episodes
            ->add(
                (new \Lukaswhite\PodcastFeedParser\Episode())
                    ->setTitle('Season One, Episode One')
                    ->setSeason(1)
                    ->setEpisodeNumber(1)
                    ->setGuid('s01e01')
            )
            ->add(
                (new \Lukaswhite\PodcastFeedParser\Episode())
                    ->setTitle('Season One, Episode Two')
                    ->setSeason(1)
                    ->setEpisodeNumber(2)
                    ->setGuid('s01e02')
            )
            ->add(
                (new \Lukaswhite\PodcastFeedParser\Episode())
                    ->setTitle('Season Three, Episode One')
                    ->setSeason(3)
                    ->setEpisodeNumber(1)
                    ->setGuid('s03e01')
            )
            ->add(
                (new \Lukaswhite\PodcastFeedParser\Episode())
                    ->setTitle('Season One, Episode Three')
                    ->setSeason(1)
                    ->setEpisodeNumber(3)
                    ->setGuid('s01e03')
            )
            ->add(
                (new \Lukaswhite\PodcastFeedParser\Episode())
                    ->setTitle('Season One, Episode Four')
                    ->setSeason(1)
                    ->setEpisodeNumber(4)
                    ->setGuid('s01e04')
            )
            ->add(
                (new \Lukaswhite\PodcastFeedParser\Episode())
                    ->setTitle('Season Two, Episode Two')
                    ->setSeason(2)
                    ->setEpisodeNumber(2)
                    ->setGuid('s01e01')
            )
            ->add(
                (new \Lukaswhite\PodcastFeedParser\Episode())
                    ->setTitle('Season Two, Episode Three')
                    ->setSeason(2)
                    ->setEpisodeNumber(3)
                    ->setGuid('s02e03')
            )
            ->add(
                (new \Lukaswhite\PodcastFeedParser\Episode())
                    ->setTitle('Season Two, Episode One')
                    ->setSeason(2)
                    ->setEpisodeNumber(1)
                    ->setGuid('s02e01')
            );
        return $episodes;
    }

    protected function getEpisodesWithEpisodeNumbers()
    {
        $episodes = new \Lukaswhite\PodcastFeedParser\Episodes();
        $episode = new \Lukaswhite\PodcastFeedParser\Episode();
        $episode->setTitle('Episode One');

        $episodes
            ->add(
                (new \Lukaswhite\PodcastFeedParser\Episode())
                    ->setTitle('Episode Two')
                    ->setEpisodeNumber(2)
                    ->setGuid('two')
            )
            ->add(
                (new \Lukaswhite\PodcastFeedParser\Episode())
                    ->setTitle('Episode Three')
                    ->setEpisodeNumber(3)
                    ->setGuid('three')
            )
            ->add(
                (new \Lukaswhite\PodcastFeedParser\Episode())
                    ->setTitle('Episode One')
                    ->setEpisodeNumber(1)
                    ->setGuid('one')
            )
            ->add(
                (new \Lukaswhite\PodcastFeedParser\Episode())
                    ->setTitle('Episode Four')
                    ->setEpisodeNumber(4)
                    ->setGuid('four')
            );
        return $episodes;
    }

}