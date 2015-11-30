<?php
myvox_set_context('inviteonly');

$title = myvox_echo('inviteonly:title');

// start building the main column of the page
$content = myvox_view_title($title);

// Add the form to this section
$content .= myvox_echo('inviteonly:content');

// layout the page
$body = myvox_view_layout('one_sidebar', array('content' => $content));

// draw the page
echo  myvox_view_page($title, $body);