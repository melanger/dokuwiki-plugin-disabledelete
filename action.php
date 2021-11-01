<?php

if(!defined('DOKU_INC')) die();

class action_plugin_disabledelete extends DokuWiki_Action_Plugin {
	public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('COMMON_WIKIPAGE_SAVE', 'BEFORE', $this, 'handle_pagesave_after');
    }

    public function handle_pagesave_after(Doku_Event $event) {
    	global $INFO;
    	if (empty($INFO['isadmin'])) {
	    	preg_match_all('/^={2,6}([^\n\r]+)={2,6}$/sm', $event->data['oldContent'], $oldHeadings);
	    	preg_match_all('/^={2,6}([^\n\r]+)={2,6}$/sm', $event->data['newContent'], $newHeadings);

	    	$missingHeadings = count(array_diff($oldHeadings[0], $newHeadings[0]));
	    	$extraHeadings = count(array_diff($newHeadings[0], $oldHeadings[0]));

	    	if (
	    		$event->data['changeType'] == DOKU_CHANGE_TYPE_DELETE
	    		|| ($event->data['changeType'] == DOKU_CHANGE_TYPE_EDIT && $missingHeadings > 0 && $extraHeadings == 0)
	    	) {
	    		$event->preventDefault();
	    	}
	    }
    }
}
