<?php
elgg_set_context('inviteonly');

$title = elgg_echo('inviteonly:title');

// start building the main column of the page
$content = elgg_view_title($title);

// Add the form to this section
$content .= elgg_echo('inviteonly:content');

// layout the page
$body = elgg_view_layout('one_sidebar', array('content' => $content));

// draw the page
echo  elgg_view_page($title, $body);