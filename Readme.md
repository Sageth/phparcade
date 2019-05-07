[![Build Status](https://travis-ci.org/Sageth/phparcade.svg?branch=master)](https://travis-ci.org/Sageth/phparcade)

## Description
This is a Responsive arcade script written in PHP that is -- in spirit -- based on the old (no longer 
supported or maintained) version of GameSiteScript 4. Nearly all of the code has been rewritten for 
better adherence to new coding standards.  A production "demo" of the front-end is always available at 
[Free Online Flash Games](https://www.phparcade.com).  Follow our [Twitter](https://twitter.com/phparcade) or
[Facebook](https://www.facebook.com/PHPArcade-271750579558482) for regular updates.

## Contributing
If you are interested in fixing issues, would like to help make this application better, or just want to chat, please join
our [PHPArcade Discord Server](http://discord.gg/wzr3PCf).

Please create your pull request against the `qa-integration` branch.  This will allow for sufficient testing of both CI/CD and manual methods.  Once it passes testing, Code Owner(s) will submit a PR against the master branch.  Or, to think of this another way:  `master` is Production.  `qa-integration` is for QA testing.

## Features (screenshots below)
* Add/edit pages via GUI
* Add/edit games manually
* Pre-configured advertising placements
* Custom languages possible via .PO files, English default
* Customizable theme modules and uploading
* Database-driven design, including stored procedures and indexed queries
* [Disqus](https://disqus.com/) Commenting System (optional)
* External email support (e.g. [Google Apps for Work](https://goo.gl/S3SgCr), Gmail)
* HTML5 games and other custom code implementations
* Internal high score system for some game types, primarily ibPro v2
* Bootstrap 3 and Bootstrap 4-based themes
* RSS Feeds
* Supports PHP7!
* User administration and profiles

## Additional Notes
* The examples provided in the [Wiki](https://github.com/Sageth/phpArcade/wiki) are for CentOS 6 using non-default
repositories. This code has not been tested on Apache. It may work with some basic .htaccess conversions.
* This code can be found on [GitHub](https://github.com/Sageth/phpArcade) and on [GitLab](https://gitlab.com/Sageth/phparcade).

## Supported/Tested Platforms:
* Please review the [.travis.yml](https://github.com/Sageth/phparcade/blob/master/.travis.yml) file in the `master` branch.
* Please note that MySQL is not tested and is not officially supported, however, it _probably_ works with MySQL 5.7+

## Support Information
* [Bug Tracker](https://github.com/Sageth/phpArcade/issues)
* [Wiki](https://github.com/Sageth/phpArcade/wiki)
* [Discord](http://discord.gg/wzr3PCf)

## Installation
* See details on the [GitHub Wiki](https://github.com/Sageth/phparcade/wiki) or [GitLab Wiki](https://gitlab.com/Sageth/phparcade/wikis/home)

## Credits
* [Binary Theme](http://www.binarytheme.com/) for use of the Responsive Theme
* [CDNJS](https://cdnjs.com/) is used CDN-hosted javascript files.
* [Google ReCaptcha](https://github.com/google/recaptcha) is used for bot verification
* [PHP Mailer](https://www.github.com/PHPMailer/PHPMailer) is used for email notifications.
* [phpArcade](https://www.phparcade.com) is made open source by permission. Please note that the maintainer(s) of this 
repository do not imply ownership or affiliation with [GameSiteScript](http://www.gamesitescript.com) or its contents.
* [JetBrains](https://www.jetbrains.com/) has been incredibly helpful and I'd like to thank them for their products and 
continued offering of an [open source license](https://www.jetbrains.com/buy/opensource/).  
![JetBrains Logo](https://www.phparcade.com/includes/images/jetbrains-variant-3.svg)

## Developers:
* Sage Russell - Project maintainer, developer. 

## Contributors
* Thank you to [BennyJake](https://github.com/BennyJake) for your contributions.

## Screenshots
![Admin Dashboard](http://i61.tinypic.com/15zj2g.png "Admin Dashboard")
![Admin Social](http://i59.tinypic.com/2cx8ftk.png "Social Settings")
![Game Management](http://i62.tinypic.com/2eehnbd.png "Game Management")
