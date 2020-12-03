# Podcast Feed Parser

A PHP library for parsing Podcast XML/RSS feeds.

## Installation

```bash
composer require lukaswhite/php-feed-parser
```

## Usage

```php
use Lukaswhite\PodcastFeedParser\Parser;

$parser = Parser();
$parser->setContent(/** raw content */);
$podcast = $parser->run();
```

or

```php
use Lukaswhite\PodcastFeedParser\Parser;

$parser = Parser();
$parser->load($filepath);
$podcast = $parser->run();
```