<?php


class ParserTest extends \PHPUnit\Framework\TestCase
{
    public function test_can_get_podcast_metadata()
    {
        $parser = new \Lukaswhite\PodcastFeedParser\Parser();
        $parser->setContent(file_get_contents('./tests/fixtures/feed.rss'));
        $podcast = $parser->run();
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Podcast::class,$podcast);
        $this->assertEquals('Podcast Help Desk™',$podcast->getTitle());
        $this->assertEquals('Podcasting tips, opinions, gear, technology and news',$podcast->getSubtitle());
        $this->assertEquals(
            'Podcasting tips, opinions, gear, technology and news from a Veteran podcaster of over 15 years.  Have a podcasting question?  Ask here at the Podcast Help Desk.',
            $podcast->getDescription()
        );
        $this->assertEquals('en',$podcast->getLanguage());
        $this->assertEquals('© 2012-2020 Podcast Help Desk',$podcast->getCopyright());
        $this->assertEquals('https://www.podcasthelpdesk.com/',$podcast->getLink());
        $this->assertEquals('Mike Dell',$podcast->getAuthor());
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Owner::class,$podcast->getOwner());
        $this->assertEquals('Mike Dell',$podcast->getOwner()->getName());
        $this->assertEquals('mike@mikedell.com',$podcast->getOwner()->getEmail());
        $this->assertEquals('mike@mikedell.com (Mike Dell)', $podcast->getManagingEditor());
        $this->assertEquals('clean',$podcast->getExplicit());
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Artwork::class,$podcast->getArtwork());
        $this->assertEquals(
            'https://www.podcasthelpdesk.com/wp-content/uploads/powerpress/phd1400_2020.jpg',
            $podcast->getArtwork()->getUri()
        );

        $this->assertTrue(is_array($podcast->getCategories()));
        $this->assertEquals(4, count($podcast->getCategories()));
        $this->assertEquals(3, count($podcast->getCategories(\Lukaswhite\PodcastFeedParser\Category::ITUNES)));
        $this->assertEquals(1, count($podcast->getCategories(\Lukaswhite\PodcastFeedParser\Category::GOOGLE_PLAY)));

        /** @var \Lukaswhite\PodcastFeedParser\Category $technology */
        $technology = $podcast->getCategories(\Lukaswhite\PodcastFeedParser\Category::ITUNES)[0];
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Category::class,$technology);
        $this->assertEquals('Technology',$technology->getName());
        $this->assertEquals(\Lukaswhite\PodcastFeedParser\Category::ITUNES,$technology->getType());
        $this->assertEquals(0,count($technology->getSubCategories()));

        /** @var \Lukaswhite\PodcastFeedParser\Category $business */
        $business = $podcast->getCategories(\Lukaswhite\PodcastFeedParser\Category::ITUNES)[1];
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Category::class,$business);
        $this->assertEquals('Business',$business->getName());
        $this->assertEquals(\Lukaswhite\PodcastFeedParser\Category::ITUNES,$business->getType());
        $this->assertEquals(1,count($business->getSubCategories()));
        /** @var \Lukaswhite\PodcastFeedParser\Category $marketing */
        $marketing = $business->getSubCategories()[0];
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Category::class,$marketing);
        $this->assertEquals('Marketing',$marketing->getName());
        $this->assertEquals(\Lukaswhite\PodcastFeedParser\Category::ITUNES,$marketing->getType());
        $this->assertEquals(0,count($marketing->getSubCategories()));

        /** @var \Lukaswhite\PodcastFeedParser\Category $gpTechnology */
        $gpTechnology = $podcast->getCategories(\Lukaswhite\PodcastFeedParser\Category::GOOGLE_PLAY)[0];
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Category::class,$gpTechnology);
        $this->assertEquals('Technology',$gpTechnology->getName());
        $this->assertEquals(\Lukaswhite\PodcastFeedParser\Category::GOOGLE_PLAY,$gpTechnology->getType());
        $this->assertEquals(0,count($gpTechnology->getSubCategories()));

    }

    public function test_can_get_episodes()
    {
        $parser = new \Lukaswhite\PodcastFeedParser\Parser();
        $parser->setContent(file_get_contents('./tests/fixtures/feed.rss'));
        $podcast = $parser->run();

        $this->assertTrue(is_array($podcast->getEpisodes()));
        $this->assertEquals(6,count($podcast->getEpisodes()));

        /** @var \Lukaswhite\PodcastFeedParser\Episode $episode */
        $episode = $podcast->getEpisodes()[0];
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Episode::class,$episode);


        $this->assertEquals('https://www.podcasthelpdesk.com/?p=775', $episode->getGuid());

        $this->assertEquals(
            'Podcast Help Desk going 2 times weekly starting December 16th – PHD151',
		    $episode->getTitle()
        );

        $this->assertEquals(
            'https://www.podcasthelpdesk.com/podcast-help-desk-going-2-times-weekly-starting-december-16th-phd151/',
            $episode->getLink()
        );

        $this->assertEquals(
            'I failed miserably at completing the NaPodPoMo 30 episodes in 30 days this year.  OH well,  Such is life getting in the way.
The Good News and part of the "big" announcement I teased is this show is going 2 times weekly starting on Dec. 16, 2020.',
            $episode->getDescription()
        );

        $this->assertEquals('clean',$episode->getExplicit());

        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Media::class,$episode->getMedia());
        $this->assertEquals(
            'https://media.blubrry.com/phd/ins.blubrry.com/phd/phd151.mp3',
            $episode->getMedia()->getUri()
        );
        $this->assertEquals(
            'audio/mpeg',
            $episode->getMedia()->getMimeType()
        );
        $this->assertEquals(
            6893874,
            $episode->getMedia()->getLength()
        );

        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Artwork::class,$episode->getArtwork());
        $this->assertEquals(
            'https://www.podcasthelpdesk.com/wp-content/uploads/powerpress/phd1400_2020.jpg',
            $episode->getArtwork()->getUri()
        );

        $this->assertInstanceOf(\DateTime::class,$episode->getPublishedDate());
        $this->assertEquals('2020-11-30 21:57:00',$episode->getPublishedDate()->format('Y-m-d H:i:s'));

    }

    public function test_does_not_break_if_podcast_metadata_missing()
    {
        $parser = new \Lukaswhite\PodcastFeedParser\Parser();
        $parser->setContent(file_get_contents('./tests/fixtures/minimal.rss'));
        $podcast = $parser->run();
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Podcast::class,$podcast);
        $this->assertEquals('Podcast Help Desk™',$podcast->getTitle());
        $this->assertNull($podcast->getSubtitle());
        $this->assertEquals(
            'Podcasting tips, opinions, gear, technology and news from a Veteran podcaster of over 15 years.  Have a podcasting question?  Ask here at the Podcast Help Desk.',
            $podcast->getDescription()
        );
        $this->assertNull($podcast->getLanguage());
        $this->assertNull($podcast->getCopyright());
        $this->assertEquals('https://www.podcasthelpdesk.com/',$podcast->getLink());
        $this->assertNull($podcast->getAuthor());
        $this->assertNull($podcast->getOwner());
        $this->assertNull($podcast->getManagingEditor());
        $this->assertNull($podcast->getExplicit());
        $this->assertNull($podcast->getArtwork());
    }

    public function test_does_not_break_if_episode_data_missing()
    {
        $parser = new \Lukaswhite\PodcastFeedParser\Parser();
        $parser->setContent(file_get_contents('./tests/fixtures/minimal.rss'));
        $podcast = $parser->run();

        $this->assertTrue(is_array($podcast->getEpisodes()));
        $this->assertEquals(1,count($podcast->getEpisodes()));

        /** @var \Lukaswhite\PodcastFeedParser\Episode $episode */
        $episode = $podcast->getEpisodes()[0];
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Episode::class,$episode);


        $this->assertNull($episode->getGuid());

        $this->assertEquals(
            'Podcast Help Desk going 2 times weekly starting December 16th – PHD151',
            $episode->getTitle()
        );

        $this->assertNull($episode->getLink());
        $this->assertNull($episode->getDescription());
        $this->assertNull($episode->getExplicit());

        $this->assertNull($episode->getMedia());

        $this->assertNull($episode->getArtwork());
    }

    public function test_can_load_from_file()
    {
        $parser = new \Lukaswhite\PodcastFeedParser\Parser();
        $parser->load('./tests/fixtures/feed.rss');
        $podcast = $parser->run();
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Podcast::class,$podcast);
        $this->assertEquals('Podcast Help Desk™',$podcast->getTitle());
    }

    /**
     * @expectedException \Lukaswhite\PodcastFeedParser\Exceptions\FileNotFoundException
     */
    public function test_throws_exception_if_file_not_found()
    {
        $parser = new \Lukaswhite\PodcastFeedParser\Parser();
        $parser->load('./tests/fixtures/i-do-not-exist.rss');
    }

    /**
     * @expectedException \Lukaswhite\PodcastFeedParser\Exceptions\InvalidXmlException
     */
    public function test_throws_exception_if_file_not_xml()
    {
        $parser = new \Lukaswhite\PodcastFeedParser\Parser();
        $parser->load('./tests/fixtures/not-xml.rss');
        $podcast = $parser->run();
    }
}