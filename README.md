## gatherassets
I had a bunch of legacy code that needed migrating.  The images all needed to move from one CDN to another, and the originals were long gone.  This class will find those remote URLS in css, less, sass, html, or php files and copy them to a local directory of your choice.  

###Usage

```php

//Establish the class and declare the target directory in the constructor
$gatherAssets = new gatherAssets('img/');

//Copy any images contained in url() arguments in CSS, LESS, or SASS files
$gatherAssets->fromCss('test.css');

//Copy any images contained in the src attribute of <img> tags in html or php files
$gatherAssets->fromHtml('test.html');

//Easily clean the target URL directory if you made a mistake or want to start over
$gatherAssets->cleanDir();

```