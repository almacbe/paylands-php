<?php

namespace WAM\Paylands\Tests;

use WAM\Paylands\Client;
use WAM\Paylands\ClientFactory;
use WAM\Paylands\DiscoveryProxy;
use WAM\Paylands\RequestFactory;

abstract class ClientBaseTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $customerExternalId;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        if (!$_ENV['ENABLE_API_INTEGRATION']) {
            $this->markTestSkipped('Api integration disabled');
        }

        $apiDiscoveryProxy = new DiscoveryProxy();

        $apiRequestFactory = new RequestFactory(
            $apiDiscoveryProxy,
            $_ENV['API_SIGNATURE']
        );

        $apiRequestFactory->setRequestFactory();

        $clientFactory = new ClientFactory(
            $apiRequestFactory,
            $apiDiscoveryProxy,
            $_ENV['API_KEY'],
            $_ENV['API_URL'],
            $_ENV['API_SANDBOX']
        );

        $clientFactory->setUriFactory();
        $clientFactory->setHttpClient();

        $this->client = $clientFactory->create();

        $this->customerExternalId = uniqid('php_');
    }
}
