<?php

namespace Hatimeria\MagentoApiBundle\Api;

use \SoapClient;
use \SoapFault;

class Api
{
    /**
     * @var \SoapClient
     */
    protected $client;
    /**
     * @var string
     */
    protected $session;
    /**
     * @var string
     */
    protected $host;
    /**
     * @var string
     */
    protected $user;
    /**
     * @var string
     */
    protected $key;
    /**
     * @var array
     */
    protected $defaults = array();

    public function __construct($host, $user, $key, $route, $defaults)
    {
        $this->host = $host . $route;
        $this->user = $user;
        $this->key  = $key;

        if (is_array($defaults)) {
            $this->defaults = $defaults;
        }
    }

    /**
     * @return SoapClient
     */
    public function getClient()
    {
        if (null === $this->client) {
            $this->client = new SoapClient($this->host);
        }

        return $this->client;
    }

    protected function getSession()
    {
        if (null === $this->session) {
            $this->resetSession();
        }

        return $this->session;
    }

    protected function generateSession()
    {
        $client  = $this->getClient();
        $session = $client->login($this->user, $this->key);

        return $session;
    }

    protected function resetSession()
    {
        $session = $this->generateSession();

        $this->session = $session;
    }

    protected function callMethod($method, $params = null, $retry = 0)
    {
        $client = $this->getClient();

        try {
            if (null !== $params) {
                $result = $client->call($this->getSession(), $method, $params);
            } else {
                $result = $client->call($this->getSession(), $method);
            }
        } catch (SoapFault $e) {
            // for now retry count is not configurable as well as ApiException codes
            // these will be changed soonish
            //@todo retry count configurable, configurable codes which can be retried
            if ($retry < 1 && in_array($e->faultcode, array(ApiException::SESSION_EXPIRED))) {
                return $this->callMethod($method, $params, ++$retry);
            }

            throw new ApiException($e->faultstring, $e->faultcode, $e);
        }

        return $result;
    }

    public function createCustomer($params)
    {
        $params = array_merge($this->defaults, $params);

        $customerId = $this->callMethod('customer.create', array($params));

        return $customerId;
    }

    public function getCustomer($customerId)
    {
        return $this->callMethod('customer.info', $customerId);
    }

    public function createCustomerAddress($customerId, $params)
    {
        $params = array_merge($this->defaults, $params);

        $customerAddressId = $this->callMethod('customer_address.create', array($customerId, $params));

        return $customerAddressId;
    }

    public function getCustomerAddress($customerAddressId)
    {
        return $this->callMethod('customer_address.info', $customerAddressId);
    }

}