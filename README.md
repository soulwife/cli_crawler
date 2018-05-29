CLI web crawler
========================

Crawl domain and get reports without any library or dependencies (no CURL, no [PSR Log](https://github.com/php-fig/log), even no Composer).
(specification of TT, you know).

**What's inside?** 
--------------

How to
----------------
Please specify a url and run script. Example: url=http://example.com php crawler.php
 

What's going on here?
---------------

 * App crawls all domain pages
 
 * Get amount of images for each page
 
 * Create report report_dd.mm.YYYY.html with sorted by amount of images data table
 
 * Log errors to logger.log
 

Report consists
----------------
  * Page url;

  * Amount of images

  * Page processing time


Enjoy!

TODO:
----------------

Add Composer and Psr Logger (when it will be out of TT)
Add Docker
Add more tests 
Add other report formats
