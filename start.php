<?php

elgg_register_event_handler('init', 'system', 'inviteonly_init');

/**
 * Initialisate the plugin
 */
function inviteonly_init() {
    elgg_register_event_handler('action', 'register', 'inviteonly_post_handler');

    elgg_unregister_page_handler('register');
    elgg_register_page_handler('register', 'inviteonly_page_handler');

    elgg_register_page_handler('invite-only', 'inviteonly_page_handler');
}

/**
 * Page handler for register and invite-only page
 *
 * @param array $page_elements Page elements
 * @param string $handler The handler string
 *
 * @return bool
 */
function inviteonly_page_handler($page_elements, $handler) {

    if ($handler == 'invite-only') {
        require_once elgg_get_plugins_path() . 'inviteonly/pages/inviteonly.php';
        return true;
    }

    if ($handler == 'register') {

        if (get_input('invitecode') && get_input('friend_guid')) {
            $friend = get_user(get_input('friend_guid'));

            if (!elgg_instanceof($friend, 'user')) {
                return inviteonly_redirect();
            }

            $friend_invitecode = elgg_validate_invite_code($friend->username, get_input('invitecode'));

            if (!$friend_invitecode) {
                return inviteonly_redirect();
            }

            require_once elgg_get_plugins_path() . 'inviteonly/pages/register.php';
            return true;
        }

        return inviteonly_redirect();
    }

    return false;
}

/**
 * Post handler for register form
 *
 * @param $event
 * @param $object_type
 * @param $object
 *
 * @return bool
 */
function inviteonly_post_handler($event, $object_type, $object) {

    if (get_input('invitecode') && get_input('friend_guid')) {
        $friend = get_user(get_input('friend_guid'));

        if (!elgg_instanceof($friend, 'user')) {
            return inviteonly_redirect();
        }

        if (!elgg_validate_invite_code($friend->username, get_input('invitecode'))) {
            return inviteonly_redirect();
        }

        return true;
    }

    return inviteonly_redirect();
}

/**
 * Redirect the user to the invite only notice page
 *
 * @return false
 */
function inviteonly_redirect() {
    forward('/invite-only');

    return false;
}
