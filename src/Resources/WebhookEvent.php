<?php

namespace WaAPI\WaAPISdk\Resources;

class WebhookEvent
{
    const MESSAGE = 'message';
    const LOADING_SCREEN = 'loading_screen';
    const QR = 'qr';
    const AUTHENTICATED = 'authenticated';
    const AUTH_FAILURE = 'auth_failure';
    const READY = 'ready';
    const DISCONNECTED = 'disconnected';
    const MESSAGE_CREATE = 'message_create';
    const MESSAGE_EDIT = 'message_edit';
    const MESSAGE_REVOKE_EVERYONE = 'message_revoke_everyone';
    const MESSAGE_REVOKE_ME = 'message_revoke_me';
    const MESSAGE_ACK = 'message_ack';
    const MESSAGE_REACTION = 'message_reaction';
    const MEDIA_UPLOADED = 'media_uploaded';
    const GROUP_JOIN = 'group_join';
    const GROUP_LEAVE = 'group_leave';
    const GROUP_UPDATE = 'group_update';
    const CHANGE_STATE = 'change_state';
    const CALL = 'call';
    const VOTE_UPDATE = 'vote_update';

    const ALL = [
        self::MESSAGE,
        self::LOADING_SCREEN,
        self::QR,
        self::AUTHENTICATED,
        self::AUTH_FAILURE,
        self::READY,
        self::DISCONNECTED,
        self::MESSAGE_CREATE,
        self::MESSAGE_EDIT,
        self::MESSAGE_REVOKE_EVERYONE,
        self::MESSAGE_REVOKE_ME,
        self::MESSAGE_ACK,
        self::MESSAGE_REACTION,
        self::MEDIA_UPLOADED,
        self::GROUP_JOIN,
        self::GROUP_LEAVE,
        self::GROUP_UPDATE,
        self::CHANGE_STATE,
        self::CALL,
        self::VOTE_UPDATE,
    ];
}
