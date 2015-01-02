# Porter

Porter helps maintain your Phproject instance.

## Features

* Purge deleted attachments
* Fix database inconsistencies
* Remove expired database sessions
* `cron` support
* More coming soon!

## Installation

`git clone` the repo into your `app/plugins` directory. If you don't need Porter to run on a schedule, you're done! Porter can always be started manually from the Administration panel within Phproject.

To schedule Porter to run at specific times, add a [crontab](http://en.wikipedia.org/wiki/Cron) entry pointing to `app/plugins/porter/cron.php`, running as frequently as you desire. There isn't much reason to run it more frequently than once per day, even on very active sites.
