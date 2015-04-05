twitterss is a simple PHP script to cross post RSS feed to the twitter. Posting rules are made with regexps, so you can extract pretty anything from feed.

# Installation and usage

```bash
git clone https://github.com/hom3chuk/twitterss.git #clone into sources
cd twitterss #change directory
composer install #install dependencies. If you do not have composer, go to https://getcomposer.org/download/
vim config.php #add twitter API params and RSS URL to config
php twitterss.php #run! Now you can add this call to CRON, for automated updates.
```
