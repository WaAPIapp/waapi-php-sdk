<?php

namespace WaAPI\WaAPISdk\Actions;

use WaAPI\WaAPISdk\Exceptions\FailedActionException;
use WaAPI\WaAPISdk\Exceptions\NotFoundException;
use WaAPI\WaAPISdk\Exceptions\ValidationException;
use WaAPI\WaAPISdk\Resources\ExecutedAction;
use WaAPI\WaAPISdk\Resources\Instance;
use WaAPI\WaAPISdk\Resources\InstanceClientMe;
use WaAPI\WaAPISdk\Resources\InstanceClientQrCode;
use WaAPI\WaAPISdk\Resources\InstanceClientStatus;
use WaAPI\WaAPISdk\Resources\WebhookSubscription;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

trait ManagesInstances
{

    /**
     * Get the collection of instances.
     *
     * @return Instance[]
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function instances()
    {
        return $this->transformCollection(
            $this->get('api/v1/instances')['instances'],
            Instance::class
        );
    }

    /**
     * Get an existing instance.
     *
     * @param int|string $instanceId
     * @return Instance
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function getInstance($instanceId)
    {
        $data = $this->get("api/v1/instances/{$instanceId}")['instance'];

        return new Instance($data, $this);
    }

    /**
     * Get the WhatsApp client status of an existing instance.
     *
     * @param int|string $instanceId
     * @return InstanceClientStatus
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function getInstanceClientStatus($instanceId)
    {
        $data = $this->get("api/v1/instances/{$instanceId}/client/status")['clientStatus'];

        return new InstanceClientStatus($data, $this);
    }

    /**
     * Get the WhatsApp client QR code of an existing instance. This is only working when the client state is "qr".
     * Otherwise an error will be thrown.
     *
     * @param int|string $instanceId
     * @return InstanceClientQrCode
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function getInstanceClientQrCode($instanceId)
    {
        $data = $this->get("api/v1/instances/{$instanceId}/client/qr")['qrCode'];

        return new InstanceClientQrCode($data, $this);
    }

    /**
     * Get information about the connected WhatsApp number via the QR code. This is only working after a successful
     * connection via the QR code.
     *
     * @param int|string $instanceId
     * @return InstanceClientMe
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function getInstanceClientInfo($instanceId)
    {
        $data = $this->get("api/v1/instances/{$instanceId}/client/me")['me'];

        return new InstanceClientMe($data, $this);
    }

    /**
     * Create a new instance.
     *
     * @param string|null $name
     * @param string|null $webhookUrl
     * @param string[]|null $webhookEvents
     * @return Instance
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function createInstance($name = null, $webhookUrl = null, $webhookEvents = [])
    {
        $payload = [];

        if ($name !== null) {
            $payload['name'] = $name;
        }

        if ($webhookUrl !== null || !empty($webhookEvents)) {
            $payload['webhook'] = [
                'url' => $webhookUrl,
                'events' => $webhookEvents ?? [],
            ];
        }

        $data = $this->post("api/v1/instances", $payload)['instance'];

        return new Instance($data, $this);
    }

    /**
     * Update an existing instance.
     *
     * @param int|string $instanceId
     * @param string|null $webhookUrl
     * @param string[]|null $webhookEvents
     * @param string|null $name
     * @return Instance
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function updateInstance($instanceId, $webhookUrl = null, $webhookEvents = [], $name = null)
    {
        $payload = [];

        if ($name !== null) {
            $payload['name'] = $name;
        }

        $payload['webhook'] = [
            'url' => $webhookUrl,
            'events' => $webhookEvents ?? [],
        ];

        $data = $this->put("api/v1/instances/{$instanceId}", $payload)['data'];

        return new Instance($data, $this);
    }

    /**
     * Delete an existing instance.
     *
     * @param int|string $instanceId
     * @return void
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function deleteInstance($instanceId)
    {
        $this->delete("api/v1/instances/{$instanceId}");
    }


    /**
     * Get the status of an async request by its reference UUID.
     *
     * @param int|string $instanceId
     * @param string $reference
     * @return array
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function getInstanceRequestStatus($instanceId, $reference)
    {
        return $this->get("api/v1/instances/{$instanceId}/request/{$reference}");
    }

    /**
     * Execute an action on the given instance. Each action returns individual/different data.
     *
     * @param int|string $instanceId
     * @param string $actionName
     * @param array $requestData
     * @return ExecutedAction
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function executeInstanceAction($instanceId, $actionName, $requestData = [])
    {
        $data = $this->post("api/v1/instances/{$instanceId}/client/action/{$actionName}", $requestData)['data'];

        return new ExecutedAction($data, $this);
    }

    /**
     * Get the webhook subscriptions registered on an instance.
     *
     * @param int|string $instanceId
     * @return WebhookSubscription[]
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function getWebhookSubscriptions($instanceId)
    {
        return $this->transformCollection(
            $this->get("api/v1/instances/{$instanceId}/webhooks", false)['data'],
            WebhookSubscription::class,
            ['instanceId' => $instanceId]
        );
    }

    /**
     * Subscribe a URL to receive instance events. Subscribing an already-registered
     * URL again returns the existing subscription instead of creating a duplicate.
     *
     * @param int|string $instanceId
     * @param string $url
     * @param string[] $events
     * @param string|null $source Who is registering this subscription (zapier, make, n8n or api). Defaults to api.
     * @return WebhookSubscription
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function createWebhookSubscription($instanceId, $url, $events, $source = null)
    {
        $payload = [
            'url' => $url,
            'events' => $events,
        ];

        if ($source !== null) {
            $payload['source'] = $source;
        }

        $data = $this->post("api/v1/instances/{$instanceId}/webhooks", $payload, false)['data'];

        return new WebhookSubscription($data + ['instanceId' => $instanceId], $this);
    }

    /**
     * Remove a webhook subscription from an instance.
     *
     * @param int|string $instanceId
     * @param int|string $subscriptionId
     * @return void
     *
     * @throws Exception | FailedActionException | NotFoundException | ValidationException | GuzzleException
     */
    public function deleteWebhookSubscription($instanceId, $subscriptionId)
    {
        $this->delete("api/v1/instances/{$instanceId}/webhooks/{$subscriptionId}");
    }


    // ---------------------------------------------------------------------
    // Generated from the OpenAPI spec by `php artisan sdk:generate-methods
    // --flavour=php` in the proxy repository. Do not hand-edit: regenerate.
    //
    // executeInstanceAction() above stays: it is the escape hatch for any
    // action newer than the last release, and it costs nothing.
    // ---------------------------------------------------------------------

    /**
     * send a text message to a chat
     */
    public function sendMessage(
        int $instanceId,
        string $chatId,
        string $message,
        ?array $mentions = null,
        ?string $replyToMessageId = null,
        ?bool $previewLink = null,
        ?bool $firedandforget = null
    ) {
        return $this->executeInstanceAction($instanceId, 'send-message', compact('chatId', 'message', 'mentions', 'replyToMessageId', 'previewLink', 'firedandforget'));
    }

    /**
     * send a media message (image, video, audio, document)
     */
    public function sendMedia(
        int $instanceId,
        string $chatId,
        ?string $mediaUrl = null,
        ?string $mediaBase64 = null,
        ?string $mediaCaption = null,
        ?string $mediaName = null,
        ?string $replyToMessageId = null,
        ?bool $previewLink = null,
        ?bool $asSticker = null,
        ?bool $asVoice = null,
        ?bool $asDocument = null,
        ?bool $firedandforget = null
    ) {
        return $this->executeInstanceAction($instanceId, 'send-media', compact('chatId', 'mediaUrl', 'mediaBase64', 'mediaCaption', 'mediaName', 'replyToMessageId', 'previewLink', 'asSticker', 'asVoice', 'asDocument', 'firedandforget'));
    }

    /**
     * mark chat messages as seen (blue ticks)
     */
    public function sendSeen(
        int $instanceId,
        string $chatId
    ) {
        return $this->executeInstanceAction($instanceId, 'send-seen', compact('chatId'));
    }

    /**
     * send vCard
     */
    public function sendVcard(
        int $instanceId,
        string $chatId,
        array $vCard,
        ?bool $firedandforget = null
    ) {
        return $this->executeInstanceAction($instanceId, 'send-vcard', compact('chatId', 'vCard', 'firedandforget'));
    }

    /**
     * send location
     */
    public function sendLocation(
        int $instanceId,
        string $chatId,
        float $latitude,
        float $longitude,
        ?array $options = null,
        ?bool $firedandforget = null
    ) {
        return $this->executeInstanceAction($instanceId, 'send-location', compact('chatId', 'latitude', 'longitude', 'options', 'firedandforget'));
    }

    /**
     * get all chats
     */
    public function getChats(
        int $instanceId,
        ?int $offset = null,
        ?int $limit = null
    ) {
        return $this->executeInstanceAction($instanceId, 'get-chats', compact('offset', 'limit'));
    }

    /**
     * mark chat as unread
     */
    public function markChatUnread(
        int $instanceId,
        string $chatId
    ) {
        return $this->executeInstanceAction($instanceId, 'mark-chat-unread', compact('chatId'));
    }

    /**
     * mute chat
     */
    public function muteChat(
        int $instanceId,
        string $chatId,
        ?string $unmuteDate = null
    ) {
        return $this->executeInstanceAction($instanceId, 'mute-chat', compact('chatId', 'unmuteDate'));
    }

    /**
     * unmute chat
     */
    public function unmuteChat(
        int $instanceId,
        string $chatId
    ) {
        return $this->executeInstanceAction($instanceId, 'unmute-chat', compact('chatId'));
    }

    /**
     * pin chat
     */
    public function pinChat(
        int $instanceId,
        string $chatId
    ) {
        return $this->executeInstanceAction($instanceId, 'pin-chat', compact('chatId'));
    }

    /**
     * unpin chat
     */
    public function unpinChat(
        int $instanceId,
        string $chatId
    ) {
        return $this->executeInstanceAction($instanceId, 'unpin-chat', compact('chatId'));
    }

    /**
     * fetch messages from a chat
     */
    public function fetchMessages(
        int $instanceId,
        string $chatId,
        ?int $limit = null,
        ?int $offset = null,
        ?bool $fromMe = null,
        ?bool $includeMedia = null
    ) {
        return $this->executeInstanceAction($instanceId, 'fetch-messages', compact('chatId', 'limit', 'offset', 'fromMe', 'includeMedia'));
    }

    /**
     * get message by ID
     */
    public function getMessageById(
        int $instanceId,
        string $messageId,
        ?bool $includeMedia = null
    ) {
        return $this->executeInstanceAction($instanceId, 'get-message-by-id', compact('messageId', 'includeMedia'));
    }

    /**
     * download media from message
     */
    public function downloadMedia(
        int $instanceId,
        string $messageId
    ) {
        return $this->executeInstanceAction($instanceId, 'download-media', compact('messageId'));
    }

    /**
     * get message info by id
     */
    public function getMessageInfoById(
        int $instanceId,
        string $messageId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-message-info-by-id', compact('messageId'));
    }

    /**
     * delete message by id
     */
    public function deleteMessageById(
        int $instanceId,
        string $messageId,
        ?bool $forEveryone = null
    ) {
        return $this->executeInstanceAction($instanceId, 'delete-message-by-id', compact('messageId', 'forEveryone'));
    }

    /**
     * edit a message
     */
    public function editMessage(
        int $instanceId,
        string $messageId,
        string $message
    ) {
        return $this->executeInstanceAction($instanceId, 'edit-message', compact('messageId', 'message'));
    }

    /**
     * forward a message
     */
    public function forwardMessage(
        int $instanceId,
        string $messageId,
        string $chatId
    ) {
        return $this->executeInstanceAction($instanceId, 'forward-message', compact('messageId', 'chatId'));
    }

    /**
     * search messages
     */
    public function searchMessages(
        int $instanceId,
        string $query,
        ?array $options = null
    ) {
        return $this->executeInstanceAction($instanceId, 'search-messages', compact('query', 'options'));
    }

    /**
     * get all contacts
     */
    public function getContacts(
        int $instanceId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-contacts', []);
    }

    /**
     * get chat ID from phone number
     */
    public function getNumberId(
        int $instanceId,
        string $number
    ) {
        return $this->executeInstanceAction($instanceId, 'get-number-id', compact('number'));
    }

    /**
     * get country code
     */
    public function getCountryCode(
        int $instanceId,
        string $number
    ) {
        return $this->executeInstanceAction($instanceId, 'get-country-code', compact('number'));
    }

    /**
     * get formatted number
     */
    public function getFormattedNumber(
        int $instanceId,
        string $number
    ) {
        return $this->executeInstanceAction($instanceId, 'get-formatted-number', compact('number'));
    }

    /**
     * is registered user
     */
    public function isRegisteredUser(
        int $instanceId,
        string $contactId
    ) {
        return $this->executeInstanceAction($instanceId, 'is-registered-user', compact('contactId'));
    }

    /**
     * create poll message
     */
    public function createPoll(
        int $instanceId,
        string $chatId,
        string $caption,
        array $options,
        ?bool $multipleAnswers = null
    ) {
        return $this->executeInstanceAction($instanceId, 'create-poll', compact('chatId', 'caption', 'options', 'multipleAnswers'));
    }

    /**
     * get poll votes
     */
    public function getPollVotes(
        int $instanceId,
        string $messageId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-poll-votes', compact('messageId'));
    }

    /**
     * [BETA] get stories
     */
    public function getStories(
        int $instanceId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-stories', []);
    }

    /**
     * [BETA] post a text or media status/story
     */
    public function postStatus(
        int $instanceId,
        ?string $content = null,
        ?string $mediaUrl = null,
        ?string $mediaCaption = null,
        ?string $mediaName = null,
        ?int $backgroundColor = null,
        ?int $fontStyle = null,
        ?bool $sendVideoAsGif = null,
        ?bool $sendAudioAsVoice = null,
        ?array $audience = null
    ) {
        return $this->executeInstanceAction($instanceId, 'post-status', compact('content', 'mediaUrl', 'mediaCaption', 'mediaName', 'backgroundColor', 'fontStyle', 'sendVideoAsGif', 'sendAudioAsVoice', 'audience'));
    }

    /**
     * [BETA] set the Status privacy
     */
    public function setStatusPrivacy(
        int $instanceId,
        string $type,
        ?array $contacts = null
    ) {
        return $this->executeInstanceAction($instanceId, 'set-status-privacy', compact('type', 'contacts'));
    }

    /**
     * [BETA] get the current Status privacy
     */
    public function getStatusPrivacy(
        int $instanceId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-status-privacy', []);
    }

    /**
     * get profile picture URL
     */
    public function getProfilePicUrl(
        int $instanceId,
        string $contactId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-profile-pic-url', compact('contactId'));
    }

    /**
     * get contact details by ID
     */
    public function getContactById(
        int $instanceId,
        string $contactId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-contact-by-id', compact('contactId'));
    }

    /**
     * get contact LID and phone
     */
    public function getLid(
        int $instanceId,
        array $contactIds
    ) {
        return $this->executeInstanceAction($instanceId, 'get-lid', compact('contactIds'));
    }

    /**
     * [BETA] add and update contact
     */
    public function upsertContact(
        int $instanceId,
        string $phoneNumber,
        string $firstName,
        string $lastName,
        ?bool $syncToAddressbook = null
    ) {
        return $this->executeInstanceAction($instanceId, 'upsert-contact', compact('phoneNumber', 'firstName', 'lastName', 'syncToAddressbook'));
    }

    /**
     * delete contact
     */
    public function deleteContact(
        int $instanceId,
        string $phoneNumber
    ) {
        return $this->executeInstanceAction($instanceId, 'delete-contact', compact('phoneNumber'));
    }

    /**
     * block a contact
     */
    public function blockContact(
        int $instanceId,
        string $contactId
    ) {
        return $this->executeInstanceAction($instanceId, 'block-contact', compact('contactId'));
    }

    /**
     * unblock a contact
     */
    public function unblockContact(
        int $instanceId,
        string $contactId
    ) {
        return $this->executeInstanceAction($instanceId, 'unblock-contact', compact('contactId'));
    }

    /**
     * get blocked contacts
     */
    public function getBlockedContacts(
        int $instanceId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-blocked-contacts', []);
    }

    /**
     * get common groups with contact
     */
    public function getCommonGroups(
        int $instanceId,
        string $contactId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-common-groups', compact('contactId'));
    }

    /**
     * get contact about info
     */
    public function getContactAboutInfo(
        int $instanceId,
        string $contactId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-contact-about-info', compact('contactId'));
    }

    /**
     * get chat by id
     */
    public function getChatById(
        int $instanceId,
        string $chatId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-chat-by-id', compact('chatId'));
    }

    /**
     * delete chat by id
     */
    public function deleteChatById(
        int $instanceId,
        string $chatId
    ) {
        return $this->executeInstanceAction($instanceId, 'delete-chat-by-id', compact('chatId'));
    }

    /**
     * create group
     */
    public function createGroup(
        int $instanceId,
        string $groupName,
        array $groupParticipants
    ) {
        return $this->executeInstanceAction($instanceId, 'create-group', compact('groupName', 'groupParticipants'));
    }

    /**
     * get group participants
     */
    public function getGroupParticipants(
        int $instanceId,
        string $chatId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-group-participants', compact('chatId'));
    }

    /**
     * get group info
     */
    public function getGroupInfo(
        int $instanceId,
        string $chatId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-group-info', compact('chatId'));
    }

    /**
     * get message reactions
     */
    public function getReactions(
        int $instanceId,
        ?string $messageId = null
    ) {
        return $this->executeInstanceAction($instanceId, 'get-reactions', compact('messageId'));
    }

    /**
     * react to message
     */
    public function reactToMessage(
        int $instanceId,
        string $messageId,
        string $reaction
    ) {
        return $this->executeInstanceAction($instanceId, 'react-to-message', compact('messageId', 'reaction'));
    }

    /**
     * update group info
     */
    public function updateGroupInfo(
        int $instanceId,
        string $chatId,
        ?string $subject = null,
        ?string $description = null,
        ?string $pictureUrl = null
    ) {
        return $this->executeInstanceAction($instanceId, 'update-group-info', compact('chatId', 'subject', 'description', 'pictureUrl'));
    }

    /**
     * get message mentions
     */
    public function getMessageMentions(
        int $instanceId,
        string $messageId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-message-mentions', compact('messageId'));
    }

    /**
     * pin message
     */
    public function pinMessage(
        int $instanceId,
        string $messageId,
        ?int $duration = null
    ) {
        return $this->executeInstanceAction($instanceId, 'pin-message', compact('messageId', 'duration'));
    }

    /**
     * unpin message
     */
    public function unpinMessage(
        int $instanceId,
        string $messageId
    ) {
        return $this->executeInstanceAction($instanceId, 'unpin-message', compact('messageId'));
    }

    /**
     * star message
     */
    public function starMessage(
        int $instanceId,
        string $messageId
    ) {
        return $this->executeInstanceAction($instanceId, 'star-message', compact('messageId'));
    }

    /**
     * unstar message
     */
    public function unstarMessage(
        int $instanceId,
        string $messageId
    ) {
        return $this->executeInstanceAction($instanceId, 'unstar-message', compact('messageId'));
    }

    /**
     * update group settings
     */
    public function updateGroupSettings(
        int $instanceId,
        string $chatId,
        ?bool $messageAdminOnly = null
    ) {
        return $this->executeInstanceAction($instanceId, 'update-group-settings', compact('chatId', 'messageAdminOnly'));
    }

    /**
     * add group participant
     */
    public function addGroupParticipant(
        int $instanceId,
        string $chatId,
        string $participant
    ) {
        return $this->executeInstanceAction($instanceId, 'add-group-participant', compact('chatId', 'participant'));
    }

    /**
     * remove group participant
     */
    public function removeGroupParticipant(
        int $instanceId,
        string $chatId,
        string $participant
    ) {
        return $this->executeInstanceAction($instanceId, 'remove-group-participant', compact('chatId', 'participant'));
    }

    /**
     * promote group participant
     */
    public function promoteGroupParticipant(
        int $instanceId,
        string $chatId,
        string $participant
    ) {
        return $this->executeInstanceAction($instanceId, 'promote-group-participant', compact('chatId', 'participant'));
    }

    /**
     * demote group participant
     */
    public function demoteGroupParticipant(
        int $instanceId,
        string $chatId,
        string $participant
    ) {
        return $this->executeInstanceAction($instanceId, 'demote-group-participant', compact('chatId', 'participant'));
    }

    /**
     * approve group membership requests
     */
    public function acceptGroupMemberRequests(
        int $instanceId,
        string $chatId,
        array|string|null $requesterIds = null
    ) {
        return $this->executeInstanceAction($instanceId, 'accept-group-member-requests', compact('chatId', 'requesterIds'));
    }

    /**
     * deny group membership requests
     */
    public function denyGroupMemberRequests(
        int $instanceId,
        string $chatId,
        array|string|null $requesterIds = null
    ) {
        return $this->executeInstanceAction($instanceId, 'deny-group-member-requests', compact('chatId', 'requesterIds'));
    }

    /**
     * get group membership requests
     */
    public function getGroupMemberRequests(
        int $instanceId,
        string $chatId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-group-member-requests', compact('chatId'));
    }

    /**
     * accept group invite
     */
    public function acceptInvite(
        int $instanceId,
        string $inviteCode
    ) {
        return $this->executeInstanceAction($instanceId, 'accept-invite', compact('inviteCode'));
    }

    /**
     * accept group invite
     */
    public function acceptGroupInvite(
        int $instanceId,
        ?string $inviteCode = null,
        ?array $inviteData = null
    ) {
        return $this->executeInstanceAction($instanceId, 'accept-group-invite', compact('inviteCode', 'inviteData'));
    }

    /**
     * get group invite info
     */
    public function getInviteInfo(
        int $instanceId,
        string $inviteCode
    ) {
        return $this->executeInstanceAction($instanceId, 'get-invite-info', compact('inviteCode'));
    }

    /**
     * create a channel
     */
    public function createChannel(
        int $instanceId,
        string $name,
        ?string $description = null,
        ?string $pictureUrl = null
    ) {
        return $this->executeInstanceAction($instanceId, 'create-channel', compact('name', 'description', 'pictureUrl'));
    }

    /**
     * get channels
     */
    public function getChannels(
        int $instanceId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-channels', []);
    }

    /**
     * get channel by id
     */
    public function getChannelById(
        int $instanceId,
        ?string $channelId = null
    ) {
        return $this->executeInstanceAction($instanceId, 'get-channel-by-id', compact('channelId'));
    }

    /**
     * subscribe to channel
     */
    public function subscribeToChannel(
        int $instanceId,
        ?string $channelId = null
    ) {
        return $this->executeInstanceAction($instanceId, 'subscribe-to-channel', compact('channelId'));
    }

    /**
     * unsubscribe from channel
     */
    public function unsubscribeFromChannel(
        int $instanceId,
        ?string $channelId = null,
        ?bool $deleteChannelData = null
    ) {
        return $this->executeInstanceAction($instanceId, 'unsubscribe-from-channel', compact('channelId', 'deleteChannelData'));
    }

    /**
     * search channels
     */
    public function searchChannels(
        int $instanceId,
        ?array $countryCodes = null,
        ?string $searchText = null,
        ?string $view = null,
        ?int $limit = null,
        ?bool $skipSubscribedNewsletters = null
    ) {
        return $this->executeInstanceAction($instanceId, 'search-channels', compact('countryCodes', 'searchText', 'view', 'limit', 'skipSubscribedNewsletters'));
    }

    /**
     * [BETA] create a community
     */
    public function createCommunity(
        int $instanceId,
        string $title,
        ?string $description = null,
        ?bool $closed = null,
        ?bool $allowNonAdminSubGroupCreation = null,
        ?bool $createGeneralChat = null,
        ?array $existingGroupIds = null
    ) {
        return $this->executeInstanceAction($instanceId, 'create-community', compact('title', 'description', 'closed', 'allowNonAdminSubGroupCreation', 'createGeneralChat', 'existingGroupIds'));
    }

    /**
     * [BETA] get communities
     */
    public function getCommunities(
        int $instanceId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-communities', []);
    }

    /**
     * [BETA] get community by id
     */
    public function getCommunityById(
        int $instanceId,
        string $communityId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-community-by-id', compact('communityId'));
    }

    /**
     * [BETA] get community subgroups
     */
    public function getCommunitySubgroups(
        int $instanceId,
        string $communityId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-community-subgroups', compact('communityId'));
    }

    /**
     * [BETA] send community announcement
     */
    public function sendCommunityAnnouncement(
        int $instanceId,
        string $communityId,
        ?string $target = null,
        ?string $message = null,
        ?string $mediaUrl = null,
        ?string $mediaCaption = null,
        ?string $mediaName = null,
        ?bool $previewLink = null,
        ?bool $asSticker = null,
        ?bool $asVoice = null,
        ?bool $asDocument = null
    ) {
        return $this->executeInstanceAction($instanceId, 'send-community-announcement', compact('communityId', 'target', 'message', 'mediaUrl', 'mediaCaption', 'mediaName', 'previewLink', 'asSticker', 'asVoice', 'asDocument'));
    }

    /**
     * [BETA] link community subgroup
     */
    public function linkCommunitySubgroup(
        int $instanceId,
        string $communityId,
        array $groupIds
    ) {
        return $this->executeInstanceAction($instanceId, 'link-community-subgroup', compact('communityId', 'groupIds'));
    }

    /**
     * [BETA] unlink community subgroup
     */
    public function unlinkCommunitySubgroup(
        int $instanceId,
        string $communityId,
        array $groupIds,
        ?bool $removeOrphanMembers = null
    ) {
        return $this->executeInstanceAction($instanceId, 'unlink-community-subgroup', compact('communityId', 'groupIds', 'removeOrphanMembers'));
    }

    /**
     * [BETA] leave community
     */
    public function leaveCommunity(
        int $instanceId,
        string $communityId
    ) {
        return $this->executeInstanceAction($instanceId, 'leave-community', compact('communityId'));
    }

    /**
     * archive chat
     */
    public function archiveChat(
        int $instanceId,
        string $chatId
    ) {
        return $this->executeInstanceAction($instanceId, 'archive-chat', compact('chatId'));
    }

    /**
     * unarchive chat
     */
    public function unarchiveChat(
        int $instanceId,
        string $chatId
    ) {
        return $this->executeInstanceAction($instanceId, 'unarchive-chat', compact('chatId'));
    }

    /**
     * get all labels
     */
    public function getLabels(
        int $instanceId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-labels', []);
    }

    /**
     * get label by id
     */
    public function getLabelById(
        int $instanceId,
        int $labelId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-label-by-id', compact('labelId'));
    }

    /**
     * get chat labels
     */
    public function getChatLabels(
        int $instanceId,
        string $chatId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-chat-labels', compact('chatId'));
    }

    /**
     * get chats by labelId
     */
    public function getChatsByLabelId(
        int $instanceId,
        int $labelId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-chats-by-label-id', compact('labelId'));
    }

    /**
     * change chat labels
     */
    public function changeChatLabels(
        int $instanceId,
        string $chatId,
        array $labelIds
    ) {
        return $this->executeInstanceAction($instanceId, 'change-chat-labels', compact('chatId', 'labelIds'));
    }

    /**
     * logout
     */
    public function logout(
        int $instanceId
    ) {
        return $this->executeInstanceAction($instanceId, 'logout', []);
    }

    /**
     * reboot instance
     */
    public function reboot(
        int $instanceId
    ) {
        return $this->executeInstanceAction($instanceId, 'reboot', []);
    }

    /**
     * send presence available
     */
    public function sendPresenceAvailable(
        int $instanceId
    ) {
        return $this->executeInstanceAction($instanceId, 'send-presence-available', []);
    }

    /**
     * set status
     */
    public function setStatus(
        int $instanceId,
        string $status
    ) {
        return $this->executeInstanceAction($instanceId, 'set-status', compact('status'));
    }

    /**
     * set display name
     */
    public function setDisplayName(
        int $instanceId,
        string $displayName
    ) {
        return $this->executeInstanceAction($instanceId, 'set-display-name', compact('displayName'));
    }

    /**
     * request pairing code
     */
    public function requestPairingCode(
        int $instanceId,
        string $phoneNumber,
        ?bool $showNotification = null
    ) {
        return $this->executeInstanceAction($instanceId, 'request-pairing-code', compact('phoneNumber', 'showNotification'));
    }

    /**
     * send typing state
     */
    public function sendTyping(
        int $instanceId,
        string $chatId
    ) {
        return $this->executeInstanceAction($instanceId, 'send-typing', compact('chatId'));
    }

    /**
     * clear chat messages
     */
    public function clearChatMessages(
        int $instanceId,
        string $chatId
    ) {
        return $this->executeInstanceAction($instanceId, 'clear-chat-messages', compact('chatId'));
    }

    /**
     * sync chat history
     */
    public function syncChatHistory(
        int $instanceId,
        string $chatId
    ) {
        return $this->executeInstanceAction($instanceId, 'sync-chat-history', compact('chatId'));
    }

    /**
     * stop typing indicator
     */
    public function sendStopTyping(
        int $instanceId,
        ?string $chatId = null
    ) {
        return $this->executeInstanceAction($instanceId, 'send-stop-typing', compact('chatId'));
    }

    /**
     * send presence unavailable
     */
    public function sendPresenceUnavailable(
        int $instanceId
    ) {
        return $this->executeInstanceAction($instanceId, 'send-presence-unavailable', []);
    }

    /**
     * [BETA] subscribe to and fetch current presence of a chat
     */
    public function getChatPresence(
        int $instanceId,
        string $chatId,
        ?bool $waitForData = null,
        ?int $timeoutMs = null
    ) {
        return $this->executeInstanceAction($instanceId, 'get-chat-presence', compact('chatId', 'waitForData', 'timeoutMs'));
    }

    /**
     * [BETA] read cached presence for a chat without subscribing
     */
    public function getChatPresenceSnapshot(
        int $instanceId,
        string $chatId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-chat-presence-snapshot', compact('chatId'));
    }

    /**
     * send event message
     */
    public function sendEvent(
        int $instanceId,
        string $chatId,
        string $name,
        int|string $startTime,
        ?string $description = null,
        int|string|null $endTime = null,
        ?string $location = null,
        ?string $callType = null
    ) {
        return $this->executeInstanceAction($instanceId, 'send-event', compact('chatId', 'name', 'startTime', 'description', 'endTime', 'location', 'callType'));
    }

    /**
     * vote on poll
     */
    public function voteOnPoll(
        int $instanceId,
        string $messageId,
        array $selectedOptions
    ) {
        return $this->executeInstanceAction($instanceId, 'vote-on-poll', compact('messageId', 'selectedOptions'));
    }

    /**
     * edit scheduled event
     */
    public function editScheduledEvent(
        int $instanceId,
        string $messageId,
        ?string $name = null,
        ?int $startTimeTs = null,
        ?array $eventSendOptions = null
    ) {
        return $this->executeInstanceAction($instanceId, 'edit-scheduled-event', compact('messageId', 'name', 'startTimeTs', 'eventSendOptions'));
    }

    /**
     * get pinned messages
     */
    public function getPinnedMessages(
        int $instanceId,
        string $chatId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-pinned-messages', compact('chatId'));
    }

    /**
     * set device name
     */
    public function setDeviceName(
        int $instanceId,
        ?string $deviceName = null,
        ?string $browserName = null
    ) {
        return $this->executeInstanceAction($instanceId, 'set-device-name', compact('deviceName', 'browserName'));
    }

    /**
     * create call link
     */
    public function createCallLink(
        int $instanceId,
        int|string $startTime,
        string $callType
    ) {
        return $this->executeInstanceAction($instanceId, 'create-call-link', compact('startTime', 'callType'));
    }

    /**
     * send event response
     */
    public function sendEventResponse(
        int $instanceId,
        int $eventResponse,
        string $eventMessageId
    ) {
        return $this->executeInstanceAction($instanceId, 'send-event-response', compact('eventResponse', 'eventMessageId'));
    }

    /**
     * revoke status message
     */
    public function revokeStatusMessage(
        int $instanceId,
        string $messageId
    ) {
        return $this->executeInstanceAction($instanceId, 'revoke-status-message', compact('messageId'));
    }

    /**
     * add customer note
     */
    public function addCustomerNote(
        int $instanceId,
        string $chatId,
        string $note
    ) {
        return $this->executeInstanceAction($instanceId, 'add-customer-note', compact('chatId', 'note'));
    }

    /**
     * get customer note
     */
    public function getCustomerNote(
        int $instanceId,
        string $chatId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-customer-note', compact('chatId'));
    }

    /**
     * get broadcast
     */
    public function getBroadcast(
        int $instanceId,
        string $contactId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-broadcast', compact('contactId'));
    }

    /**
     * revoke status
     */
    public function revokeStatus(
        int $instanceId,
        string $messageId
    ) {
        return $this->executeInstanceAction($instanceId, 'revoke-status', compact('messageId'));
    }

    /**
     * [BETA] get privacy settings
     */
    public function getPrivacySettings(
        int $instanceId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-privacy-settings', []);
    }

    /**
     * [BETA] set privacy setting
     */
    public function setPrivacySetting(
        int $instanceId,
        string $category,
        string $value,
        ?array $disallowedList = null
    ) {
        return $this->executeInstanceAction($instanceId, 'set-privacy-setting', compact('category', 'value', 'disallowedList'));
    }

    /**
     * [BETA] get disappearing messages
     */
    public function getDisappearingMessages(
        int $instanceId,
        string $chatId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-disappearing-messages', compact('chatId'));
    }

    /**
     * [BETA] set disappearing messages
     */
    public function setDisappearingMessages(
        int $instanceId,
        string $chatId,
        int $duration
    ) {
        return $this->executeInstanceAction($instanceId, 'set-disappearing-messages', compact('chatId', 'duration'));
    }

    /**
     * [BETA] get disappearing durations
     */
    public function getDisappearingDurations(
        int $instanceId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-disappearing-durations', []);
    }

    /**
     * [BETA] get business profile
     */
    public function getBusinessProfile(
        int $instanceId,
        ?string $userId = null,
        ?bool $withCompliance = null
    ) {
        return $this->executeInstanceAction($instanceId, 'get-business-profile', compact('userId', 'withCompliance'));
    }

    /**
     * [BETA] get business categories
     */
    public function getBusinessCategories(
        int $instanceId,
        ?string $parentId = null
    ) {
        return $this->executeInstanceAction($instanceId, 'get-business-categories', compact('parentId'));
    }

    /**
     * [BETA] set business profile
     */
    public function setBusinessProfile(
        int $instanceId,
        ?string $description = null,
        ?string $email = null,
        ?string $address = null,
        ?float $latitude = null,
        ?float $longitude = null,
        ?array $website = null,
        ?array $categories = null,
        ?array $businessHours = null,
        ?string $priceTier = null,
        ?array $serviceAreas = null
    ) {
        return $this->executeInstanceAction($instanceId, 'set-business-profile', compact('description', 'email', 'address', 'latitude', 'longitude', 'website', 'categories', 'businessHours', 'priceTier', 'serviceAreas'));
    }

    /**
     * [BETA] get quick replies
     */
    public function getQuickReplies(
        int $instanceId
    ) {
        return $this->executeInstanceAction($instanceId, 'get-quick-replies', []);
    }

    /**
     * [BETA] create quick reply
     */
    public function createQuickReply(
        int $instanceId,
        string $shortcut,
        string $message,
        ?array $keywords = null
    ) {
        return $this->executeInstanceAction($instanceId, 'create-quick-reply', compact('shortcut', 'message', 'keywords'));
    }

    /**
     * [BETA] update quick reply
     */
    public function updateQuickReply(
        int $instanceId,
        string $id,
        ?string $shortcut = null,
        ?string $message = null,
        ?array $keywords = null
    ) {
        return $this->executeInstanceAction($instanceId, 'update-quick-reply', compact('id', 'shortcut', 'message', 'keywords'));
    }

    /**
     * [BETA] delete quick reply
     */
    public function deleteQuickReply(
        int $instanceId,
        string $id
    ) {
        return $this->executeInstanceAction($instanceId, 'delete-quick-reply', compact('id'));
    }
}
