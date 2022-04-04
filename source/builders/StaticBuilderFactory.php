<?php

namespace specialist\fcm\source\builders;

use specialist\fcm\source\builders\apiV1\MessageOptionsBuilder;
use specialist\fcm\source\builders\legacyApi\MessageOptionsBuilder as LegacyMessageOptionsBuilder;
use specialist\fcm\source\requests\ApiV1Request;
use specialist\fcm\source\requests\LegacyApiRequest;
use specialist\fcm\source\requests\Request;
use InvalidArgumentException;

/**
 * Class StaticBuilderFactory.
 */
final class StaticBuilderFactory
{
    const FOR_TOKEN_SENDING = 'for_token_sending';
    const FOR_TOPIC_SENDING = 'for_topic_sending';
    const FOR_GROUP_SENDING = 'for_group_sending';
    const FOR_TOPIC_MANAGEMENT = 'for_topic_management';
    const FOR_GROUP_MANAGEMENT = 'for_group_management';
    const UNKNOWN_REASON = 'unknown_reason';

    const AVAILABLE_BUILDERS = [
        self::FOR_TOKEN_SENDING,
        self::FOR_TOPIC_SENDING,
        self::FOR_GROUP_SENDING,
        self::FOR_TOPIC_MANAGEMENT,
    ];

    const LEGACY_MESSAGE_BUILDERS = [
        self::FOR_TOKEN_SENDING,
        self::FOR_TOPIC_SENDING,
        self::FOR_GROUP_SENDING,
    ];

    /**
     * @param string $reason
     * @param Request $request
     *
     * @return OptionsBuilder
     */
    public static function build(string $reason, Request $request): OptionsBuilder
    {
        if (static::FOR_TOPIC_MANAGEMENT === $reason) {
            return new TopicSubscriptionOptionsBuilder();
        }

        if (static::FOR_GROUP_MANAGEMENT === $reason) {
            return new GroupManagementOptionsBuilder();
        }

        if ($request instanceof LegacyApiRequest && \in_array($reason, self::LEGACY_MESSAGE_BUILDERS, true)) {
            return new LegacyMessageOptionsBuilder();
        }

        if ($request instanceof ApiV1Request && static::FOR_TOKEN_SENDING === $reason) {
            return new MessageOptionsBuilder();
        }

        throw new InvalidArgumentException('reason param must be in ['.implode(', ', static::AVAILABLE_BUILDERS).'].');
    }
}
