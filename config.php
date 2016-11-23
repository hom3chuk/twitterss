<?php
/**
 * Config file for twitterss. This one is used for https://twitter.com/php_odesk
 */
return [
    // URL of the feed
    'feed-url' => 'https://www.upwork.com/ab/feed/jobs/rss?skills=php&amp;api_params=1',

    // Regexp to extract from RSS elements
    'feed-extract' => [
        'link' => '#(.*)#', // post link to job first
        'description' => '#Budget<\/b>\:\s*(\$\d+?)\s*<br#ism', // try get a budget and post it too
        'title' => '#(.*)\s*-\s*Upwork#', // job title
    ],

    // String to glue parts from `feed-extract`
    'feed-extract-glue' => "\n",

    // Twitter API parameters. See https://dev.twitter.com/oauth/overview,
    // or https://dev.twitter.com/oauth/overview/application-owner-access-tokens specifically
    'twitter-api-consumer-key' => '',
    'twitter-api-consumer-secret' => '',
    'twitter-api-access-token' => '',
    'twitter-api-access-token-secret' => '',
    'twitter-api-timeout' => 1,
];

