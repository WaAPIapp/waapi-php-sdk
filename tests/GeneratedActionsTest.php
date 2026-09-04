<?php

declare(strict_types=1);

namespace WaAPI\WaAPISdk\Tests;

use PHPUnit\Framework\TestCase;

/**
 * One test per generated action method: the action name, that the instance id
 * lands in the URL rather than the payload, and the exact body that carried the
 * rest -- all against a faked transport.
 *
 * These methods hold no logic, so the failures they can actually have are a
 * wrong action string and a parameter dropped from compact(). Both are visible
 * in the outgoing request and nowhere else, because a dropped field still
 * produces a successful call.
 *
 * Sample values carry their own parameter name, so two adjacent parameters
 * swapped fails here instead of passing with a complete-looking payload.
 *
 * Generated alongside the methods -- regenerate both together.
 */
class GeneratedActionsTest extends TestCase
{
    use FakesTheApi;

    public function test_send_message_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->sendMessage(1, 'chatId-value', 'message-value');

        $this->assertActionCalled('send-message', [
            'chatId' => 'chatId-value',
            'message' => 'message-value',
        ]);
    }

    public function test_send_media_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->sendMedia(1, 'chatId-value');

        $this->assertActionCalled('send-media', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_send_seen_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->sendSeen(1, 'chatId-value');

        $this->assertActionCalled('send-seen', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_send_vcard_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->sendVcard(1, 'chatId-value', ['vCard-one', 'vCard-two']);

        $this->assertActionCalled('send-vcard', [
            'chatId' => 'chatId-value',
            'vCard' => ['vCard-one', 'vCard-two'],
        ]);
    }

    public function test_send_location_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->sendLocation(1, 'chatId-value', 1.5, 1.5);

        $this->assertActionCalled('send-location', [
            'chatId' => 'chatId-value',
            'latitude' => 1.5,
            'longitude' => 1.5,
        ]);
    }

    public function test_get_chats_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getChats(1);

        $this->assertActionCalled('get-chats', []);
    }

    public function test_mark_chat_unread_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->markChatUnread(1, 'chatId-value');

        $this->assertActionCalled('mark-chat-unread', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_mute_chat_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->muteChat(1, 'chatId-value');

        $this->assertActionCalled('mute-chat', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_unmute_chat_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->unmuteChat(1, 'chatId-value');

        $this->assertActionCalled('unmute-chat', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_pin_chat_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->pinChat(1, 'chatId-value');

        $this->assertActionCalled('pin-chat', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_unpin_chat_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->unpinChat(1, 'chatId-value');

        $this->assertActionCalled('unpin-chat', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_fetch_messages_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->fetchMessages(1, 'chatId-value');

        $this->assertActionCalled('fetch-messages', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_get_message_by_id_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getMessageById(1, 'messageId-value');

        $this->assertActionCalled('get-message-by-id', [
            'messageId' => 'messageId-value',
        ]);
    }

    public function test_download_media_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->downloadMedia(1, 'messageId-value');

        $this->assertActionCalled('download-media', [
            'messageId' => 'messageId-value',
        ]);
    }

    public function test_get_message_info_by_id_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getMessageInfoById(1, 'messageId-value');

        $this->assertActionCalled('get-message-info-by-id', [
            'messageId' => 'messageId-value',
        ]);
    }

    public function test_delete_message_by_id_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->deleteMessageById(1, 'messageId-value');

        $this->assertActionCalled('delete-message-by-id', [
            'messageId' => 'messageId-value',
        ]);
    }

    public function test_edit_message_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->editMessage(1, 'messageId-value', 'message-value');

        $this->assertActionCalled('edit-message', [
            'messageId' => 'messageId-value',
            'message' => 'message-value',
        ]);
    }

    public function test_forward_message_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->forwardMessage(1, 'messageId-value', 'chatId-value');

        $this->assertActionCalled('forward-message', [
            'messageId' => 'messageId-value',
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_search_messages_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->searchMessages(1, 'query-value');

        $this->assertActionCalled('search-messages', [
            'query' => 'query-value',
        ]);
    }

    public function test_get_contacts_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getContacts(1);

        $this->assertActionCalled('get-contacts', []);
    }

    public function test_get_number_id_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getNumberId(1, 'number-value');

        $this->assertActionCalled('get-number-id', [
            'number' => 'number-value',
        ]);
    }

    public function test_get_country_code_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getCountryCode(1, 'number-value');

        $this->assertActionCalled('get-country-code', [
            'number' => 'number-value',
        ]);
    }

    public function test_get_formatted_number_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getFormattedNumber(1, 'number-value');

        $this->assertActionCalled('get-formatted-number', [
            'number' => 'number-value',
        ]);
    }

    public function test_is_registered_user_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->isRegisteredUser(1, 'contactId-value');

        $this->assertActionCalled('is-registered-user', [
            'contactId' => 'contactId-value',
        ]);
    }

    public function test_create_poll_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->createPoll(1, 'chatId-value', 'caption-value', ['options-one', 'options-two']);

        $this->assertActionCalled('create-poll', [
            'chatId' => 'chatId-value',
            'caption' => 'caption-value',
            'options' => ['options-one', 'options-two'],
        ]);
    }

    public function test_get_poll_votes_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getPollVotes(1, 'messageId-value');

        $this->assertActionCalled('get-poll-votes', [
            'messageId' => 'messageId-value',
        ]);
    }

    public function test_get_stories_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getStories(1);

        $this->assertActionCalled('get-stories', []);
    }

    public function test_post_status_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->postStatus(1);

        $this->assertActionCalled('post-status', []);
    }

    public function test_set_status_privacy_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->setStatusPrivacy(1, 'type-value');

        $this->assertActionCalled('set-status-privacy', [
            'type' => 'type-value',
        ]);
    }

    public function test_get_status_privacy_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getStatusPrivacy(1);

        $this->assertActionCalled('get-status-privacy', []);
    }

    public function test_get_profile_pic_url_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getProfilePicUrl(1, 'contactId-value');

        $this->assertActionCalled('get-profile-pic-url', [
            'contactId' => 'contactId-value',
        ]);
    }

    public function test_get_contact_by_id_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getContactById(1, 'contactId-value');

        $this->assertActionCalled('get-contact-by-id', [
            'contactId' => 'contactId-value',
        ]);
    }

    public function test_get_lid_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getLid(1, ['contactIds-one', 'contactIds-two']);

        $this->assertActionCalled('get-lid', [
            'contactIds' => ['contactIds-one', 'contactIds-two'],
        ]);
    }

    public function test_upsert_contact_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->upsertContact(1, 'phoneNumber-value', 'firstName-value', 'lastName-value');

        $this->assertActionCalled('upsert-contact', [
            'phoneNumber' => 'phoneNumber-value',
            'firstName' => 'firstName-value',
            'lastName' => 'lastName-value',
        ]);
    }

    public function test_delete_contact_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->deleteContact(1, 'phoneNumber-value');

        $this->assertActionCalled('delete-contact', [
            'phoneNumber' => 'phoneNumber-value',
        ]);
    }

    public function test_block_contact_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->blockContact(1, 'contactId-value');

        $this->assertActionCalled('block-contact', [
            'contactId' => 'contactId-value',
        ]);
    }

    public function test_unblock_contact_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->unblockContact(1, 'contactId-value');

        $this->assertActionCalled('unblock-contact', [
            'contactId' => 'contactId-value',
        ]);
    }

    public function test_get_blocked_contacts_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getBlockedContacts(1);

        $this->assertActionCalled('get-blocked-contacts', []);
    }

    public function test_get_common_groups_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getCommonGroups(1, 'contactId-value');

        $this->assertActionCalled('get-common-groups', [
            'contactId' => 'contactId-value',
        ]);
    }

    public function test_get_contact_about_info_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getContactAboutInfo(1, 'contactId-value');

        $this->assertActionCalled('get-contact-about-info', [
            'contactId' => 'contactId-value',
        ]);
    }

    public function test_get_chat_by_id_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getChatById(1, 'chatId-value');

        $this->assertActionCalled('get-chat-by-id', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_delete_chat_by_id_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->deleteChatById(1, 'chatId-value');

        $this->assertActionCalled('delete-chat-by-id', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_create_group_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->createGroup(1, 'groupName-value', ['groupParticipants-one', 'groupParticipants-two']);

        $this->assertActionCalled('create-group', [
            'groupName' => 'groupName-value',
            'groupParticipants' => ['groupParticipants-one', 'groupParticipants-two'],
        ]);
    }

    public function test_get_group_participants_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getGroupParticipants(1, 'chatId-value');

        $this->assertActionCalled('get-group-participants', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_get_group_info_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getGroupInfo(1, 'chatId-value');

        $this->assertActionCalled('get-group-info', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_get_reactions_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getReactions(1);

        $this->assertActionCalled('get-reactions', []);
    }

    public function test_react_to_message_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->reactToMessage(1, 'messageId-value', 'reaction-value');

        $this->assertActionCalled('react-to-message', [
            'messageId' => 'messageId-value',
            'reaction' => 'reaction-value',
        ]);
    }

    public function test_update_group_info_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->updateGroupInfo(1, 'chatId-value');

        $this->assertActionCalled('update-group-info', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_get_message_mentions_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getMessageMentions(1, 'messageId-value');

        $this->assertActionCalled('get-message-mentions', [
            'messageId' => 'messageId-value',
        ]);
    }

    public function test_pin_message_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->pinMessage(1, 'messageId-value');

        $this->assertActionCalled('pin-message', [
            'messageId' => 'messageId-value',
        ]);
    }

    public function test_unpin_message_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->unpinMessage(1, 'messageId-value');

        $this->assertActionCalled('unpin-message', [
            'messageId' => 'messageId-value',
        ]);
    }

    public function test_star_message_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->starMessage(1, 'messageId-value');

        $this->assertActionCalled('star-message', [
            'messageId' => 'messageId-value',
        ]);
    }

    public function test_unstar_message_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->unstarMessage(1, 'messageId-value');

        $this->assertActionCalled('unstar-message', [
            'messageId' => 'messageId-value',
        ]);
    }

    public function test_update_group_settings_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->updateGroupSettings(1, 'chatId-value');

        $this->assertActionCalled('update-group-settings', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_add_group_participant_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->addGroupParticipant(1, 'chatId-value', 'participant-value');

        $this->assertActionCalled('add-group-participant', [
            'chatId' => 'chatId-value',
            'participant' => 'participant-value',
        ]);
    }

    public function test_remove_group_participant_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->removeGroupParticipant(1, 'chatId-value', 'participant-value');

        $this->assertActionCalled('remove-group-participant', [
            'chatId' => 'chatId-value',
            'participant' => 'participant-value',
        ]);
    }

    public function test_promote_group_participant_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->promoteGroupParticipant(1, 'chatId-value', 'participant-value');

        $this->assertActionCalled('promote-group-participant', [
            'chatId' => 'chatId-value',
            'participant' => 'participant-value',
        ]);
    }

    public function test_demote_group_participant_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->demoteGroupParticipant(1, 'chatId-value', 'participant-value');

        $this->assertActionCalled('demote-group-participant', [
            'chatId' => 'chatId-value',
            'participant' => 'participant-value',
        ]);
    }

    public function test_accept_group_member_requests_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->acceptGroupMemberRequests(1, 'chatId-value');

        $this->assertActionCalled('accept-group-member-requests', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_deny_group_member_requests_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->denyGroupMemberRequests(1, 'chatId-value');

        $this->assertActionCalled('deny-group-member-requests', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_get_group_member_requests_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getGroupMemberRequests(1, 'chatId-value');

        $this->assertActionCalled('get-group-member-requests', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_accept_invite_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->acceptInvite(1, 'inviteCode-value');

        $this->assertActionCalled('accept-invite', [
            'inviteCode' => 'inviteCode-value',
        ]);
    }

    public function test_accept_group_invite_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->acceptGroupInvite(1);

        $this->assertActionCalled('accept-group-invite', []);
    }

    public function test_get_invite_info_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getInviteInfo(1, 'inviteCode-value');

        $this->assertActionCalled('get-invite-info', [
            'inviteCode' => 'inviteCode-value',
        ]);
    }

    public function test_create_channel_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->createChannel(1, 'name-value');

        $this->assertActionCalled('create-channel', [
            'name' => 'name-value',
        ]);
    }

    public function test_get_channels_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getChannels(1);

        $this->assertActionCalled('get-channels', []);
    }

    public function test_get_channel_by_id_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getChannelById(1);

        $this->assertActionCalled('get-channel-by-id', []);
    }

    public function test_subscribe_to_channel_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->subscribeToChannel(1);

        $this->assertActionCalled('subscribe-to-channel', []);
    }

    public function test_unsubscribe_from_channel_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->unsubscribeFromChannel(1);

        $this->assertActionCalled('unsubscribe-from-channel', []);
    }

    public function test_search_channels_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->searchChannels(1);

        $this->assertActionCalled('search-channels', []);
    }

    public function test_create_community_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->createCommunity(1, 'title-value');

        $this->assertActionCalled('create-community', [
            'title' => 'title-value',
        ]);
    }

    public function test_get_communities_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getCommunities(1);

        $this->assertActionCalled('get-communities', []);
    }

    public function test_get_community_by_id_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getCommunityById(1, 'communityId-value');

        $this->assertActionCalled('get-community-by-id', [
            'communityId' => 'communityId-value',
        ]);
    }

    public function test_get_community_subgroups_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getCommunitySubgroups(1, 'communityId-value');

        $this->assertActionCalled('get-community-subgroups', [
            'communityId' => 'communityId-value',
        ]);
    }

    public function test_send_community_announcement_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->sendCommunityAnnouncement(1, 'communityId-value');

        $this->assertActionCalled('send-community-announcement', [
            'communityId' => 'communityId-value',
        ]);
    }

    public function test_link_community_subgroup_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->linkCommunitySubgroup(1, 'communityId-value', ['groupIds-one', 'groupIds-two']);

        $this->assertActionCalled('link-community-subgroup', [
            'communityId' => 'communityId-value',
            'groupIds' => ['groupIds-one', 'groupIds-two'],
        ]);
    }

    public function test_unlink_community_subgroup_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->unlinkCommunitySubgroup(1, 'communityId-value', ['groupIds-one', 'groupIds-two']);

        $this->assertActionCalled('unlink-community-subgroup', [
            'communityId' => 'communityId-value',
            'groupIds' => ['groupIds-one', 'groupIds-two'],
        ]);
    }

    public function test_leave_community_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->leaveCommunity(1, 'communityId-value');

        $this->assertActionCalled('leave-community', [
            'communityId' => 'communityId-value',
        ]);
    }

    public function test_archive_chat_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->archiveChat(1, 'chatId-value');

        $this->assertActionCalled('archive-chat', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_unarchive_chat_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->unarchiveChat(1, 'chatId-value');

        $this->assertActionCalled('unarchive-chat', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_get_labels_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getLabels(1);

        $this->assertActionCalled('get-labels', []);
    }

    public function test_get_label_by_id_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getLabelById(1, 42);

        $this->assertActionCalled('get-label-by-id', [
            'labelId' => 42,
        ]);
    }

    public function test_get_chat_labels_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getChatLabels(1, 'chatId-value');

        $this->assertActionCalled('get-chat-labels', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_get_chats_by_label_id_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getChatsByLabelId(1, 42);

        $this->assertActionCalled('get-chats-by-label-id', [
            'labelId' => 42,
        ]);
    }

    public function test_change_chat_labels_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->changeChatLabels(1, 'chatId-value', ['labelIds-one', 'labelIds-two']);

        $this->assertActionCalled('change-chat-labels', [
            'chatId' => 'chatId-value',
            'labelIds' => ['labelIds-one', 'labelIds-two'],
        ]);
    }

    public function test_logout_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->logout(1);

        $this->assertActionCalled('logout', []);
    }

    public function test_reboot_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->reboot(1);

        $this->assertActionCalled('reboot', []);
    }

    public function test_send_presence_available_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->sendPresenceAvailable(1);

        $this->assertActionCalled('send-presence-available', []);
    }

    public function test_set_status_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->setStatus(1, 'status-value');

        $this->assertActionCalled('set-status', [
            'status' => 'status-value',
        ]);
    }

    public function test_set_display_name_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->setDisplayName(1, 'displayName-value');

        $this->assertActionCalled('set-display-name', [
            'displayName' => 'displayName-value',
        ]);
    }

    public function test_request_pairing_code_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->requestPairingCode(1, 'phoneNumber-value');

        $this->assertActionCalled('request-pairing-code', [
            'phoneNumber' => 'phoneNumber-value',
        ]);
    }

    public function test_send_typing_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->sendTyping(1, 'chatId-value');

        $this->assertActionCalled('send-typing', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_clear_chat_messages_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->clearChatMessages(1, 'chatId-value');

        $this->assertActionCalled('clear-chat-messages', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_sync_chat_history_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->syncChatHistory(1, 'chatId-value');

        $this->assertActionCalled('sync-chat-history', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_send_stop_typing_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->sendStopTyping(1);

        $this->assertActionCalled('send-stop-typing', []);
    }

    public function test_send_presence_unavailable_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->sendPresenceUnavailable(1);

        $this->assertActionCalled('send-presence-unavailable', []);
    }

    public function test_get_chat_presence_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getChatPresence(1, 'chatId-value');

        $this->assertActionCalled('get-chat-presence', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_get_chat_presence_snapshot_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getChatPresenceSnapshot(1, 'chatId-value');

        $this->assertActionCalled('get-chat-presence-snapshot', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_send_event_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->sendEvent(1, 'chatId-value', 'name-value', 'startTime-value');

        $this->assertActionCalled('send-event', [
            'chatId' => 'chatId-value',
            'name' => 'name-value',
            'startTime' => 'startTime-value',
        ]);
    }

    public function test_vote_on_poll_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->voteOnPoll(1, 'messageId-value', ['selectedOptions-one', 'selectedOptions-two']);

        $this->assertActionCalled('vote-on-poll', [
            'messageId' => 'messageId-value',
            'selectedOptions' => ['selectedOptions-one', 'selectedOptions-two'],
        ]);
    }

    public function test_edit_scheduled_event_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->editScheduledEvent(1, 'messageId-value');

        $this->assertActionCalled('edit-scheduled-event', [
            'messageId' => 'messageId-value',
        ]);
    }

    public function test_get_pinned_messages_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getPinnedMessages(1, 'chatId-value');

        $this->assertActionCalled('get-pinned-messages', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_set_device_name_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->setDeviceName(1);

        $this->assertActionCalled('set-device-name', []);
    }

    public function test_create_call_link_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->createCallLink(1, 'startTime-value', 'callType-value');

        $this->assertActionCalled('create-call-link', [
            'startTime' => 'startTime-value',
            'callType' => 'callType-value',
        ]);
    }

    public function test_send_event_response_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->sendEventResponse(1, 42, 'eventMessageId-value');

        $this->assertActionCalled('send-event-response', [
            'eventResponse' => 42,
            'eventMessageId' => 'eventMessageId-value',
        ]);
    }

    public function test_revoke_status_message_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->revokeStatusMessage(1, 'messageId-value');

        $this->assertActionCalled('revoke-status-message', [
            'messageId' => 'messageId-value',
        ]);
    }

    public function test_add_customer_note_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->addCustomerNote(1, 'chatId-value', 'note-value');

        $this->assertActionCalled('add-customer-note', [
            'chatId' => 'chatId-value',
            'note' => 'note-value',
        ]);
    }

    public function test_get_customer_note_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getCustomerNote(1, 'chatId-value');

        $this->assertActionCalled('get-customer-note', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_get_broadcast_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getBroadcast(1, 'contactId-value');

        $this->assertActionCalled('get-broadcast', [
            'contactId' => 'contactId-value',
        ]);
    }

    public function test_revoke_status_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->revokeStatus(1, 'messageId-value');

        $this->assertActionCalled('revoke-status', [
            'messageId' => 'messageId-value',
        ]);
    }

    public function test_get_privacy_settings_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getPrivacySettings(1);

        $this->assertActionCalled('get-privacy-settings', []);
    }

    public function test_set_privacy_setting_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->setPrivacySetting(1, 'category-value', 'value-value');

        $this->assertActionCalled('set-privacy-setting', [
            'category' => 'category-value',
            'value' => 'value-value',
        ]);
    }

    public function test_get_disappearing_messages_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getDisappearingMessages(1, 'chatId-value');

        $this->assertActionCalled('get-disappearing-messages', [
            'chatId' => 'chatId-value',
        ]);
    }

    public function test_set_disappearing_messages_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->setDisappearingMessages(1, 'chatId-value', 42);

        $this->assertActionCalled('set-disappearing-messages', [
            'chatId' => 'chatId-value',
            'duration' => 42,
        ]);
    }

    public function test_get_disappearing_durations_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getDisappearingDurations(1);

        $this->assertActionCalled('get-disappearing-durations', []);
    }

    public function test_get_business_profile_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getBusinessProfile(1);

        $this->assertActionCalled('get-business-profile', []);
    }

    public function test_get_business_categories_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getBusinessCategories(1);

        $this->assertActionCalled('get-business-categories', []);
    }

    public function test_set_business_profile_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->setBusinessProfile(1);

        $this->assertActionCalled('set-business-profile', []);
    }

    public function test_get_quick_replies_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->getQuickReplies(1);

        $this->assertActionCalled('get-quick-replies', []);
    }

    public function test_create_quick_reply_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->createQuickReply(1, 'shortcut-value', 'message-value');

        $this->assertActionCalled('create-quick-reply', [
            'shortcut' => 'shortcut-value',
            'message' => 'message-value',
        ]);
    }

    public function test_update_quick_reply_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->updateQuickReply(1, 'id-value');

        $this->assertActionCalled('update-quick-reply', [
            'id' => 'id-value',
        ]);
    }

    public function test_delete_quick_reply_sends_the_right_action(): void
    {
        $sdk = $this->fakeAction();

        $sdk->deleteQuickReply(1, 'id-value');

        $this->assertActionCalled('delete-quick-reply', [
            'id' => 'id-value',
        ]);
    }
}
