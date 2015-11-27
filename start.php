<?php

myvox_register_event_handler('init', 'system', 'inviteonly_init');

/**
 * Initialisate the plugin
 */
function inviteonly_init() {
    myvox_register_event_handler('action', 'register', 'inviteonly_post_handler');

    myvox_unregister_page_handler('register');
    myvox_register_page_handler('register', 'inviteonly_page_handler');
}

/**
 * Page handler for register page
 *
 * @param array $page_elements Page elements
 * @param string $handler The handler string
 *
 * @return bool
 */
function inviteonly_page_handler($page_elements, $handler) {

    if ($handler != 'register') {
        return inviteonly_redirect();
    }

    if (get_input('invitecode') && get_input('friend_guid')) {
        $friend = get_user(get_input('friend_guid'));

        if (!myvox_instanceof($friend, 'user')) {
            return inviteonly_redirect();
        }

        $friend_invitecode = generate_invite_code($friend->username);

        if ($friend_invitecode !== get_input('invitecode')) {
            return inviteonly_redirect();
        }

        require_once myvox_get_root_path() . 'pages/account/register.php';

        return true;
    }

    return inviteonly_redirect();
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

        if (!myvox_instanceof($friend, 'user')) {
            return inviteonly_redirect();
        }

        $friend_invitecode = generate_invite_code($friend->username);

        if ($friend_invitecode !== get_input('invitecode')) {
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