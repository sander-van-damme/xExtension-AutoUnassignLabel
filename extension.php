<?php
class AutoUnassignLabelExtension extends Minz_Extension
{
	public function init()
	{
		$this->registerHook('freshrss_user_maintenance', array($this, 'unassignLabels'));
	}

	public function unassignLabels()
	{
		$entryDao = FreshRSS_Factory::createEntryDao();
		$tagDao = FreshRSS_Factory::createTagDao();

		$entries = $entryDao->selectAll();
		foreach ($entries as $entry) {
			# Unassign labels from read entries.
			if ($entry["is_read"]) {
				$entryId = $entry["id"];
				$entryTags = $tagDao->getTagsForEntry($entryId);
				foreach ($entryTags as $entryTag) {
					$tagId = $entryTag["id"];
					$tagDao->tagEntry($tagId, $entryId, false);
				}
			}
		}
	}
}
