#!/usr/bin/php
<?php
/**
 * @package  Porter
 * @author   Alan Hardman <alan@phpizza.com>
 * @version  1.0.0
 */

require __DIR__.'../../../cron/base.php';

$porter = \Plugin\Porter\Base::instance();
$porter->run();
