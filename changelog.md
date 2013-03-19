mfw - MicroFrameWork Changelog
===============================

0.01
-------------------------------

* General improvements to make it more universal and reusable

0.1
-------------------------------

* Re-built `$mfw::depends()` to import the library from the existing mfw instance if its already been loaded, so we dont load the same library every time it gets used
* Improved commenting
* Added logging via `$mfw::log()`

0.2
-------------------------------

* Fixed dependency importing
* Made some additions to view/hello.php
* A little bit of performance improvements
* Included jQuery locally
* Fixed some comments

0.21
-------------------------------

* Removed some unnecessary code
* Updated some comments
* Some slight optimizations

0.22
-------------------------------

* Fixed use of deprecated function ereg() in `/libraries/mail.lib.php`, line `423`, now uses preg_match