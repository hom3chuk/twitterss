<?php

namespace hom3chuk\twitterss;

use \Abraham\TwitterOAuth\TwitterOAuth;

/**
 * Gets RSS feed and posts it to twitter.
 * @author hom3chuk@gmail.com
 */
class Poster
{

    private $config = [];
    /** @var \Feed $reader */
    private $reader = null;
    /** @var TwitterOAuth $twitter */
    private $twitter = null;
    /** @var array $data */
    private $data = [];
    /** @var int $lastTiemstamp */
    private $lastTimestamp = 0;

    /**
     * @param array $config Optional config array to override default one.
     */
    public function __construct($config = [])
    {
        $this->loadConfig($config);
    }

    public function post()
    {
        return $this->loadFeed()->postData();
    }

    /**
     * @return $this
     */
    public function loadFeed()
    {
        $this->initFeed()->initLatest();
        $this->data = [];

        foreach ($this->reader->item as $item) {
            if ($item->timestamp <= $this->lastTimestamp) {
                continue;
            }
            $twitData = [];
            foreach ($this->config['feed-extract'] as $field => $pattern) {
                if (0 < preg_match($pattern, (string)$item->$field, $match)) {
                    $twitData[] = html_entity_decode($match[1]);
                }
            }
            if (!empty($twitData)) {
                $this->data[(string)$item->timestamp] = implode($this->config['feed-extract-glue'], $twitData);
            }
        }

        ksort($this->data);
        return $this;
    }

    /**
     * @param array $config Optional config array to override default one.
     * @return $this
     */
    private function loadConfig($config = [])
    {
        $this->config = require_once dirname(__FILE__) . '/../config.php';
        $this->config = array_merge($this->config, $config);
        return $this;
    }

    /**
     * @return int amount of tweets posted
     */
    private function postData()
    {
        $this->initTwitter();
        $count = 0;

        foreach ($this->data as $currentTimestamp => $currentData) {
            $this->twitter->post('statuses/update', ['status' => $currentData]);
            if (200 == $this->twitter->getLastHttpCode()) {
                file_put_contents(dirname(__FILE__) . '/../last-entry.lock', $currentTimestamp);
                sleep($this->config['twitter-api-timeout']);
                $count++;
            }
        }

        return $count;
    }

    /**
     * @return $this
     * @throws \FeedException
     */
    private function initFeed()
    {
        if (!$this->reader) {
            $this->reader = \Feed::loadRss($this->config['feed-url']);
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function initTwitter()
    {
        if (!$this->twitter) {
            $this->twitter = new TwitterOAuth(
                $this->config['twitter-api-consumer-key'],
                $this->config['twitter-api-consumer-secret'],
                $this->config['twitter-api-access-token'],
                $this->config['twitter-api-access-token-secret']
            );
        }

        return $this;
    }

    private function initLatest()
    {
        if (is_file($filename = dirname(__FILE__) . '/../last-entry.lock')) {
            $this->lastTimestamp = (int)file_get_contents($filename);
        }
    }
}
