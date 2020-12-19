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
        $this->assertEquals(0,count($technology->getChildren()));

        /** @var \Lukaswhite\PodcastFeedParser\Category $business */
        $business = $podcast->getCategories(\Lukaswhite\PodcastFeedParser\Category::ITUNES)[1];
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Category::class,$business);
        $this->assertEquals('Business',$business->getName());
        $this->assertEquals(\Lukaswhite\PodcastFeedParser\Category::ITUNES,$business->getType());
        $this->assertEquals(1,count($business->getChildren()));
        /** @var \Lukaswhite\PodcastFeedParser\Category $marketing */
        $marketing = $business->getChild('Marketing');
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Category::class,$marketing);
        $this->assertEquals('Marketing',$marketing->getName());
        $this->assertEquals(\Lukaswhite\PodcastFeedParser\Category::ITUNES,$marketing->getType());
        $this->assertEquals(0,count($marketing->getChildren()));

        /** @var \Lukaswhite\PodcastFeedParser\Category $gpTechnology */
        $gpTechnology = $podcast->getCategories(\Lukaswhite\PodcastFeedParser\Category::GOOGLE_PLAY)[0];
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Category::class,$gpTechnology);
        $this->assertEquals('Technology',$gpTechnology->getName());
        $this->assertEquals(\Lukaswhite\PodcastFeedParser\Category::GOOGLE_PLAY,$gpTechnology->getType());
        $this->assertEquals(0,count($gpTechnology->getChildren()));

    }

    public function test_can_get_standard_rss_fields()
    {
        $parser = new \Lukaswhite\PodcastFeedParser\Parser();
        $parser->setContent(file_get_contents('./tests/fixtures/feed.rss'));
        $podcast = $parser->run();
        $this->assertEquals('https://wordpress.org/?v=5.5.1', $podcast->getGenerator());
        $this->assertInstanceOf(\DateTime::class,$podcast->getLastBuildDate());
        $this->assertEquals('2020-11-30 21:57:33',$podcast->getLastBuildDate()->format('Y-m-d H:i:s'));
        $this->assertEquals('episodic',$podcast->getType());
        $this->assertTrue($podcast->isEpisodic());
    }

    public function test_can_get_atom_fields()
    {
        $parser = new \Lukaswhite\PodcastFeedParser\Parser();
        $parser->setContent(file_get_contents('./tests/fixtures/feed.rss'));
        $podcast = $parser->run();
        $this->assertTrue(is_array($podcast->getAtomLinks()));
        $this->assertEquals(2, count($podcast->getAtomLinks()));

        /** @var \Lukaswhite\PodcastFeedParser\Link $link */
        $link = $podcast->getAtomLinks()[0];
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Link::class,$link);
        $this->assertEquals('https://www.podcasthelpdesk.com/feed/podcast/',$link->getUri());
        $this->assertEquals('self',$link->getRel());
        $this->assertEquals('application/rss+xml',$link->getType());
    }

    public function test_can_get_syndication_fields()
    {
        $parser = new \Lukaswhite\PodcastFeedParser\Parser();
        $parser->setContent(file_get_contents('./tests/fixtures/feed.rss'));
        $podcast = $parser->run();
        $this->assertEquals('hourly',$podcast->getUpdatePeriod());
        $this->assertEquals(1,$podcast->getUpdateFrequency());
        $this->assertInstanceOf(\DateTime::class,$podcast->getUpdateBase());
        $this->assertEquals('2020-01-01 12:00:00',$podcast->getUpdateBase()->format('Y-m-d H:i:s'));
    }

    public function test_can_get_itunes_fields()
    {
        $parser = new \Lukaswhite\PodcastFeedParser\Parser();
        $parser->setContent(file_get_contents('./tests/fixtures/feed.rss'));
        $podcast = $parser->run();
        $this->assertEquals('https://www.podcasthelpdesk.com/feed/podcast/', $podcast->getNewFeedUrl());
    }

    public function test_can_get_rawvoice_fields()
    {
        $parser = new \Lukaswhite\PodcastFeedParser\Parser();
        $parser->setContent(file_get_contents('./tests/fixtures/feed.rss'));
        $podcast = $parser->run();
        $this->assertEquals('TV-G', $podcast->getRawvoiceRating());
        $this->assertEquals('Traverse City, Michigan', $podcast->getRawvoiceLocation());
        $this->assertEquals('Twice Weekly', $podcast->getRawvoiceFrequency());
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Rawvoice\Subscribe::class,$podcast->getRawvoiceSubscribe());
        $links = $podcast->getRawvoiceSubscribe();
        $this->assertTrue(is_array($links->getLinks()));
        $this->assertArrayHasKey(\Lukaswhite\PodcastFeedParser\Rawvoice\Subscribe::FEED, $links->getLinks());
        $this->assertArrayHasKey(\Lukaswhite\PodcastFeedParser\Rawvoice\Subscribe::HTML, $links->getLinks());
        $this->assertArrayHasKey(\Lukaswhite\PodcastFeedParser\Rawvoice\Subscribe::ITUNES, $links->getLinks());
        $this->assertArrayHasKey(\Lukaswhite\PodcastFeedParser\Rawvoice\Subscribe::BLUBRRY, $links->getLinks());
        $this->assertArrayHasKey(\Lukaswhite\PodcastFeedParser\Rawvoice\Subscribe::TUNEIN, $links->getLinks());
        $this->assertArrayHasKey(\Lukaswhite\PodcastFeedParser\Rawvoice\Subscribe::STITCHER, $links->getLinks());
        $this->assertEquals('https://www.podcasthelpdesk.com/feed/podcast/',$links->getLink(
            \Lukaswhite\PodcastFeedParser\Rawvoice\Subscribe::FEED
        ));
        $this->assertEquals('https://www.podcasthelpdesk.com/subscribe-to-podcast/',$links->getLink(
            \Lukaswhite\PodcastFeedParser\Rawvoice\Subscribe::HTML
        ));
        $this->assertEquals('https://itunes.apple.com/us/podcast/podcast-help-desk/id939440023?mt=2',$links->getLink(
            \Lukaswhite\PodcastFeedParser\Rawvoice\Subscribe::ITUNES
        ));
        $this->assertEquals('https://www.blubrry.com/phd/',$links->getLink(
            \Lukaswhite\PodcastFeedParser\Rawvoice\Subscribe::BLUBRRY
        ));
        $this->assertEquals('http://tunein.com/radio/Podcast-Help-Desk-p615263/',$links->getLink(
            \Lukaswhite\PodcastFeedParser\Rawvoice\Subscribe::TUNEIN
        ));
        $this->assertEquals('https://www.stitcher.com/show/podcasting-tech-coach',$links->getLink(
            \Lukaswhite\PodcastFeedParser\Rawvoice\Subscribe::STITCHER
        ));
    }

    public function test_can_get_episodes()
    {
        $parser = new \Lukaswhite\PodcastFeedParser\Parser();
        $parser->setContent(file_get_contents('./tests/fixtures/feed.rss'));
        $podcast = $parser->run();

        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Episodes::class, $podcast->getEpisodes());
        $this->assertEquals(6,count($podcast->getEpisodes()));

        /** @var \Lukaswhite\PodcastFeedParser\Episode $episode */
        $episode = $podcast->getEpisodes()[0];
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Episode::class,$episode);


        $this->assertEquals('https://www.podcasthelpdesk.com/?p=775', $episode->getGuid());
        $this->assertTrue($episode->guidIsPermalink());

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

        $this->assertEquals('7:10',$episode->getDuration());

        $this->assertEquals('Mike Dell', $episode->getAuthor());

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

    public function test_can_get_episodes_with_episode_numbers_seasons_and_types()
    {
        $parser = new \Lukaswhite\PodcastFeedParser\Parser();
        $parser->setContent(file_get_contents('./tests/fixtures/seasons.rss'));
        $podcast = $parser->run();

        $this->assertTrue($podcast->isSerial());

        /** @var \Lukaswhite\PodcastFeedParser\Episode $episode */
        $episode = $podcast->getEpisodes()[0];
        $this->assertEquals(5,$episode->getEpisodeNumber());
        $this->assertEquals(2,$episode->getSeason());
        $this->assertEquals('full',$episode->getType());

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

        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Episodes::class,$podcast->getEpisodes());
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

    public function test_can_sort_by_most_recent()
    {
        $parser = new \Lukaswhite\PodcastFeedParser\Parser();
        $parser->load('./tests/fixtures/feed.rss');
        $podcast = $parser->run();
        $podcast->getEpisodes()->newestFirst();
        $this->assertEquals(
            'https://www.podcasthelpdesk.com/?p=775',
            $podcast->getEpisodes()->first()->getGuid()
        );
        $this->assertEquals(
            'https://www.podcasthelpdesk.com/?p=749',
            $podcast->getEpisodes()->last()->getGuid()
        );
        $this->assertEquals(
            'https://www.podcasthelpdesk.com/?p=775',
            $podcast->getEpisodes()->mostRecent()->getGuid()
        );
    }

    public function test_can_sort_by_oldest()
    {
        $parser = new \Lukaswhite\PodcastFeedParser\Parser();
        $parser->load('./tests/fixtures/feed.rss');
        $podcast = $parser->run();
        $podcast->getEpisodes()->oldestFirst();
        $this->assertEquals(
            'https://www.podcasthelpdesk.com/?p=749',
            $podcast->getEpisodes()->first()->getGuid()
        );
        $this->assertEquals(
            'https://www.podcasthelpdesk.com/?p=775',
            $podcast->getEpisodes()->last()->getGuid()
        );
    }

    public function test_can_override_description_behavior()
    {
        $config = new \Lukaswhite\PodcastFeedParser\Config();
        $config->descriptionOnly();
        $parser = new \Lukaswhite\PodcastFeedParser\Parser($config);
        $parser->load('./tests/fixtures/no-description.rss');
        $podcast = $parser->run();
        $this->assertNull($podcast->getDescription());
    }

    public function test_overriding_description_behavior_doesnt_affect_description()
    {
        $config = new \Lukaswhite\PodcastFeedParser\Config();
        $config->descriptionOnly();
        $parser = new \Lukaswhite\PodcastFeedParser\Parser($config);
        $parser->load('./tests/fixtures/feed.rss');
        $podcast = $parser->run();
        $this->assertEquals(
            'Podcasting tips, opinions, gear, technology and news from a Veteran podcaster of over 15 years.  Have a podcasting question?  Ask here at the Podcast Help Desk.',
            $podcast->getDescription()
        );
    }

    public function test_can_override_pub_date_behavior()
    {
        $config = new \Lukaswhite\PodcastFeedParser\Config();
        $config->dontDefaultToToday();
        $parser = new \Lukaswhite\PodcastFeedParser\Parser($config);
        $parser->load('./tests/fixtures/episode-with-no-pub-date.rss');
        $podcast = $parser->run();
        $this->assertNull($podcast->getEpisodes()->first()->getPublishedDate());
    }

    public function test_overriding_pub_date_behavior_does_not_affect_pub_date()
    {
        $config = new \Lukaswhite\PodcastFeedParser\Config();
        $config->dontDefaultToToday();
        $parser = new \Lukaswhite\PodcastFeedParser\Parser($config);
        $parser->load('./tests/fixtures/feed.rss');
        $podcast = $parser->run();
        $this->assertInstanceOf(\DateTime::class,$podcast->getEpisodes()->first()->getPublishedDate());
        $this->assertEquals('2020-11-30 21:57',$podcast->getEpisodes()->first()->getPublishedDate()->format('Y-m-d H:i'));
    }

    public function test_can_extract_image()
    {
        $parser = new \Lukaswhite\PodcastFeedParser\Parser();
        $parser->load('./tests/fixtures/serial.rss');
        $podcast = $parser->run();

        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Image::class, $podcast->getImage());

        $this->assertEquals('https://serialpodcast.org',$podcast->getImage()->getLink());
        $this->assertEquals('Serial',$podcast->getImage()->getTitle());
        $this->assertEquals(
            'https://image.simplecastcdn.com/images/521189a6-a4f6-404d-85cf-455a989a10a4/abbe2292-3127-41d5-b418-f43bf7ffb7b5/3000x3000/serial-itunes-logo.png?aid=rss_feed',
            $podcast->getImage()->getUrl()
        );

    }

    public function test_can_load_from_file()
    {
        $parser = new \Lukaswhite\PodcastFeedParser\Parser();
        $parser->load('./tests/fixtures/feed.rss');
        $podcast = $parser->run();
        $this->assertInstanceOf(\Lukaswhite\PodcastFeedParser\Podcast::class,$podcast);
        $this->assertEquals('Podcast Help Desk™',$podcast->getTitle());
    }

    public function test_throws_exception_if_file_not_found()
    {
        $this->expectException(\Lukaswhite\PodcastFeedParser\Exceptions\FileNotFoundException::class);
        $parser = new \Lukaswhite\PodcastFeedParser\Parser();
        $parser->load('./tests/fixtures/i-do-not-exist.rss');
    }

    public function test_throws_exception_if_file_not_xml()
    {
        $this->expectException(\Lukaswhite\PodcastFeedParser\Exceptions\InvalidXmlException::class);
        $parser = new \Lukaswhite\PodcastFeedParser\Parser();
        $parser->load('./tests/fixtures/not-xml.rss');
        $podcast = $parser->run();
    }
}