<?php

namespace Omnipay\MobilPay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use stdClass;

/**
 * MobilPay Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * @var string
     */
    protected $action;

    /**
     * @var stdClass
     */
    protected $responseError;

    /**
     * @var int
     */
    protected $errorCode;

    /**
     * @var string
     */
    protected $status;

    /**
     * @param RequestInterface $request
     * @param array $data
     * @param stdClass $responseError
     */
    public function __construct(RequestInterface $request, $data, $responseError)
    {
        parent::__construct($request, $data);

        $this->request = $request;
        $this->responseError = $responseError;

        if (isset($data['objPmNotify']['action'])) {
            $this->action = $data['objPmNotify']['action'];
        }

        if (isset($data['objPmNotify']['errorCode'])) {
            $this->errorCode = $data['objPmNotify']['errorCode'];
        }
    }

    /**
     * Response code
     *
     * @return null|string A response code from the payment gateway
     */
    public function getCode()
    {
        return $this->errorCode;
    }

    /**
     * Returns whether the transaction was successful
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->getCode() == 0 && in_array($this->action, ['confirmed']);
    }

    /**
     * Returns whether the transaction is pending
     *
     * @return boolean
     */
    public function isPending()
    {
        if ($this->getCode() == 0) {
            return in_array($this->action, ['confirmed_pending', 'paid_pending', 'paid']);
        }

        return parent::isPending();
    }

    /**
     * Is the transaction cancelled by the user?
     *
     * @return boolean
     */
    public function isCancelled()
    {
        if ($this->getCode() == 0) {
            return in_array($this->action, ['canceled']);
        }

        return $this->getCode() != 0;
    }

    /**
     * Is the transaction refunded?
     *
     * @return boolean
     */
    public function isRefunded()
    {
        if ($this->getCode() == 0) {
            return in_array($this->action, ['credit']);
        }

        return false;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Send IPN response
     *
     * @return void
     */
    public function sendResponse()
    {
        header('Content-type: application/xml');
        echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

        if ($this->responseError->code == 0) {
            echo "<crc>{$this->responseError->message}</crc>";
        } else {
            echo "<crc error_type=\"{$this->responseError->type}\" error_code=\"{$this->responseError->code}\">{$this->responseError->message}</crc>";
        }
    }
}
