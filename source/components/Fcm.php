<?php

namespace specialist\fcm\source\components;

use specialist\fcm\source\builders\StaticBuilderFactory;
use specialist\fcm\source\requests\AbstractRequest;
use specialist\fcm\source\requests\GroupManagementRequest;
use specialist\fcm\source\requests\Request;
use specialist\fcm\source\requests\StaticRequestFactory;
use specialist\fcm\source\responses\StaticResponseFactory;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionProperty;
use yii\base\Component;

/**
 * Class Fcm.
 */
class Fcm extends Component
{
    /** @var $api  */
    public $apiVersion;

    /** @var $oldApiParams array */
    public $apiParams;

    /**
     * @param string $reason A reason to create request for.
     * Can be: for topic management or for message sending (for default).
     *
     * @return Request|AbstractRequest|GroupManagementRequest
     *
     * @throws \ReflectionException
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function createRequest(string $reason = StaticBuilderFactory::FOR_TOKEN_SENDING): Request
    {
        $this->validateConfigs();
        $request = StaticRequestFactory::build($this->apiVersion, $this->apiParams, $reason);
        $request->setResponse(StaticResponseFactory::build($this->apiVersion, $request));

        return $request;
    }

    /**
     * Validates required params.
     *
     * @throws \ReflectionException
     * @throws InvalidArgumentException
     */
    private function validateConfigs()
    {
        foreach ((new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PUBLIC) as $param) {
            if (! $this->{$param->getName()}) {
                throw new InvalidArgumentException($param->getName().' param must be set.');
            }
        }
    }
}
