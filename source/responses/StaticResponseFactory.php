<?php

namespace specialist\fcm\source\responses;

use specialist\fcm\source\builders\StaticBuilderFactory;
use specialist\fcm\source\requests\AbstractRequest;
use specialist\fcm\source\requests\Request;
use specialist\fcm\source\responses\apiV1\TokenResponse;
use specialist\fcm\source\responses\legacyApi\GroupManagementResponse;
use specialist\fcm\source\responses\legacyApi\TokenResponse as LegacyTokenResponse;
use specialist\fcm\source\responses\legacyApi\TopicResponse as LegacyTopicResponse;
use specialist\fcm\source\responses\legacyApi\GroupResponse as LegacyGroupResponse;

/**
 * Class StaticResponseFactory.
 */
final class StaticResponseFactory
{
    const LEGACY_API = 'legacy_api';
    const API_V1 = 'api_v1';
    const AVAILABLE_API_VERSIONS = [
        self::LEGACY_API,
        self::API_V1,
    ];

    /**
     * @param string $apiVersion
     *
     * @param AbstractRequest|Request $request
     *
     * @return AbstractResponse
     *
     * @throws \InvalidArgumentException
     */
    public static function build(string $apiVersion, Request $request): AbstractResponse
    {
        if (StaticBuilderFactory::FOR_TOPIC_MANAGEMENT === $request->getReason()) {
            return new TopicSubscribeResponse();
        }

        if (static::LEGACY_API === $apiVersion) {
            if (StaticBuilderFactory::FOR_TOKEN_SENDING === $request->getReason()) {
                return new LegacyTokenResponse();
            }
            if (StaticBuilderFactory::FOR_TOPIC_SENDING === $request->getReason()) {
                return new LegacyTopicResponse();
            }
            if (StaticBuilderFactory::FOR_GROUP_SENDING === $request->getReason()) {
                return new LegacyGroupResponse();
            }
            if (StaticBuilderFactory::FOR_GROUP_MANAGEMENT === $request->getReason()) {
                return new GroupManagementResponse();
            }
        }

        if (static::API_V1 === $apiVersion && StaticBuilderFactory::FOR_TOKEN_SENDING === $request->getReason()) {
            return new TokenResponse();
        }

        throw new \InvalidArgumentException('api param must be in ['.implode(', ', static::AVAILABLE_API_VERSIONS).'].');
    }
}
