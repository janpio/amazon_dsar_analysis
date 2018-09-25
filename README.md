# amazon_dsar_analysis

PHP Scripts to analyse the [results of an Amazon.de "Data Subject Access Request" / "Datenauskunft"](http://betamode.de/2018/09/18/results-of-an-amazon-de-datenauskunft/).

I tweeted the resulsts of these scripts while developing them, so [have a look at this Twitter thread](https://threadreaderapp.com/thread/1042392922719748097.html) to get an impression of what the analysis looks like (and what it contained for me): https://threadreaderapp.com/thread/1042392922719748097.html

## Instructions

1. Download or clone this code
2. Copy over your DSAR result to `Amazon Datenauskunft/Jan Piotrowski` or better adapt the path in `includes/path.php`
3. Open `index.htm` on a local webserver or upload to any host with PHP

### ASIN Cache

- `content.php` writes all discovered ASINs into `cache/asins.json`. 
- Run `scrape_asin_from_amazon.php` to fill that array with scraped title + author information (30 ASINs at a time, be careful/slow or Amazon will ban/captcha you [I could scrape ~500 ASINs with no problem]).
- Reload `content.php` to see the scraped data appear.

## Support

I wrote this for myself, it works with my data.  
But of course feel free to [open an issue](https://github.com/janpio/amazon_dsar_analysis/issues/new) if it doesn't work for you or your find any bugs, and I will try to help you.
