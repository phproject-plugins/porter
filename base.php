<?php
/**
 * @package  Porter
 * @author   Alan Hardman <alan@phpizza.com>
 * @version  1.0.0
 */

namespace Plugin\Porter;

class Base extends \Plugin {

	/**
	 * Initialize the plugin
	 */
	public function _load() {

	}

	/**
	 * Check if plugin is installed
	 * @return bool
	 */
	public function _installed() {
		return true;
	}

	/**
	 * Generate page for admin panel
	 */
	public function _admin() {
		$f3 = \Base::instance();
		if($f3->get("POST.run")) {
			$this->run();
		}
		$f3->set("UI", $f3->get("UI") . ";./app/plugin/porter/");
		echo \Helper\View::instance()->render("view/admin.html");
	}

	/**
	 * Run all Porter tasks
	 */
	public function run() {
		$f3 = \Base::instance();
		$db = $f3->get("db.instance");

		if($debug = $f3->get("DEBUG")) {
			$log = new \Log("porter.log");
		}

		// Purge deleted files
		$file = new \Model\Issue\File;
		$files = $file->find("deleted_date IS NOT NULL AND deleted_date < DATE_SUB(NOW(), INTERVAL 1 DAY)");
		foreach($files as $f) {
			$result = @unlink($f->disk_filename);
			if($debug) {
				if($result) {
					$log->write("Deleted " . $f->disk_filename);
				} else {
					$log->write("Failed to delete " . $f->disk_filename);
				}
			}
		}

		// Clean up database
		$rows = $db->exec("UPDATE issue SET parent_id = NULL WHERE parent_id = '0'");
		if($debug) {
			$log->write("Fixed parent_id on {$rows} issues");
		}

		$rows = $db->exec("UPDATE issue SET closed_date = NULL WHERE closed_date = '0000-00-00 00:00:00'");
		if($debug) {
			$log->write("Fixed closed_date on {$rows} issues");
		}

		$rows = $db->exec("UPDATE issue SET deleted_date = NULL WHERE deleted_date = '0000-00-00 00:00:00'");
		if($debug) {
			$log->write("Fixed deleted_date on {$rows} issues");
		}

		$rows = $db->exec("UPDATE issue SET repeat_cycle = '' WHERE repeat_cycle = 'none'");
		if($debug) {
			$log->write("Cleaned repeat_cycle on {$rows} issues");
		}

		$rows = $db->exec("UPDATE user SET api_key = NULL WHERE api_key = ''");
		if($debug) {
			$log->write("Cleaned api_key on {$rows} users");
		}

		$rows = $db->exec("DELETE FROM session WHERE stamp < UNIX_TIMESTAMP() - ?", $f3->get("JAR.expire"));
		if($debug) {
			$log->write("Deleted {$rows} old sessions");
		}

	}

}
