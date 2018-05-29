CLI web crawler
========================

Crawl domain and get reports without any library or dependencies (no сURL, no [PSR Log](https://github.com/php-fig/log), even no Composer).
(specification of TT, you know). Requires PHP 7.+


How to
----------------
Please specify an url and run a script. Example: 

`url=http://example.com php crawler.php`

Or with Docker:

`docker run --rm -v $(pwd):/app -w /app -e url='https://example.com/' php:cli php crawler.php`
 

What's going on here?
---------------

 * App crawls all domain pages
 
 * Gets amount of images for each page
 
 * Creates report (report_dd.mm.YYYY.html) with sorted by amount of images data table
 
 * Logs errors to logger.log
 

Report consists
----------------
  * Page url

  * Amount of images on page

  * Page processing time


Enjoy!

TODO:
----------------

- [ ]  Add Composer and Psr Logger (when it will be out of TT)

- [ ]  Add more tests 

- [ ] Add other report formats
