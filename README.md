# Podcast Feed Parser

A PHP library for parsing Podcast XML/RSS feeds.

## Features

* Get channel metadata, such as the title and description
* Retrieve a list of the episodes
* Supports iTunes metadata such as categories
* Get artwork and media files 
* Sort episodes by their publication date, episode number or split into seasons

## Installation

```bash
composer require lukaswhite/php-feed-parser
```

## Usage

```php
use Lukaswhite\PodcastFeedParser\Parser;

$parser = Parser();
$parser->load('/path/to/feed/feed.rss');
$podcast = $parser->run();
```

or

```php
$parser = Parser();
$parser->setContent(/** raw content */);
$podcast = $parser->run();
```

The `run()` method returns an instance of the `Podcast` class, on which the `getEpisodes()` method returns a collection of the podcast episodes.

## Simple Example

This only shows a limited selection of the available fields; you'll find a [complete list here](https://htmlpreview.github.io/?https://github.com/lukaswhite/podcast-feed-parser/blob/main/docs/html/classes/Lukaswhite_PodcastFeedParser_Podcast.xhtml).

```php
$podcast = $parser->run();
$id = $db->insert(
    'podcasts',
    [
        'title' =>  $podcast->getTitle(),
        'description' => $podcast->getDescription(),
        'artwork' => $podcast->getArtwork()->getUri(),
    ]
);

foreach($podcast->getEpisodes() as $episode) {
    $db->insert(
        'episodes',
        [
            'podcast_id' => $id,
            'guid' => $episode->getGuid(),
            'title' =>  $episode->getTitle(),
            'description' => $episode->getDescription(),
            'media_uri' => $podcast->getMedia()->getUri(),
        ]
    );
}

return $podcast->getEpisodes()->mostRecent();
``` 