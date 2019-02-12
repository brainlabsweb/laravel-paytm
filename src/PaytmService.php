<?php

namespace Brainlabsweb\Paytm;

use Brainlabsweb\Paytm\src\core\Core;
use Brainlabsweb\Paytm\src\Exceptions\PaytmException;
use Brainlabsweb\Paytm\src\Traits\PaytmCheckout;
use Brainlabsweb\Paytm\src\Traits\PaytmRefund;
use Brainlabsweb\Paytm\src\Traits\PaytmRefundStatus;
use Brainlabsweb\Paytm\src\Traits\TransactionStatus;
use Illuminate\Support\Facades\Config;

class PaytmService extends Core
{
    use TransactionStatus, PaytmCheckout, PaytmRefund, PaytmRefundStatus;

    private $config;
    private $merchant_key;
    private $merchant_id;
    private $order_prefix;
    private $channel;
    private $industry_type;
    private $website;
    private $callback_url;
    private $response;

    const TXN_MANDATE_FIELDS = ['MID', 'ORDER_ID', 'CUST_ID', 'TXN_AMOUNT', 'CHANNEL_ID',
        'WEBSITE', 'CALLBACK_URL', 'INDUSTRY_TYPE_ID'];

    const REFUND_MANDATE_FIELDS = ['MID', 'TXNID','ORDERID', 'REFID', 'TXNTYPE','REFUNDAMOUNT'];

    const REFUND_STATUS_MANDATE_FIELDS = ['MID','ORDERID', 'REFID'];

    public function __construct()
    {
        $this->config = Config::get('paytm');

        $this->merchant_key = $this->config['credentials']['merchant_key'];
        $this->merchant_id = $this->config['credentials']['merchant_mid'];
        $this->callback_url = $this->config['credentials']['callback_url'];

        $this->website = $this->config['website'];
        $this->industry_type = $this->config['industry_type'];
        $this->channel = $this->config['channel'];
        $this->order_prefix = $this->config['order_prefix'];
    }

    /**
     * @param mixed $response
     * @return PaytmService
     */
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTxnUrl()
    {
        return $this->config['urls'][$this->config['default']]['txn_url'];
    }

    /**
     * @return mixed
     */
    public function getTransactionStatusUrl()
    {
        return $this->config['urls'][$this->config['default']]['txn_status_url'];
    }

    /**
     * @return mixed
     */
    public function getRefundUrl()
    {
        return $this->config['urls'][$this->config['default']]['refund_url'];
    }

    /**
     * @return mixed
     */
    public function getRefundStatusUrl()
    {
        return $this->config['urls'][$this->config['default']]['refund_status_url'];
    }


    /**
     * @return mixed
     */
    public function response()
    {
        return $this->setResponse(request()->all())->response;
    }

    /**
     * validate the mandatory fields
     * @param $params
     * @param $mode
     * @throws PaytmException
     */
    private function validate($params, $mode)
    {
        $diff = array_diff_assoc($mode, $params);
        foreach ($diff as $key) {
            if (!isset($params[$key])) {
                throw (new PaytmException('No value found for ' . $key));
            }
        }
    }



}
