<?php

namespace Brainlabsweb\Paytm\src\Traits;

trait TransactionStatus
{
    /**
     * @param string $order_id
     * @return mixed
     */
    public function getTransactionStatus(string $order_id)
    {
        $params = ['ORDER_ID' => $order_id, 'MID' => $this->merchant_id];

        $params['CHECKSUMHASH'] = $this->getChecksumFromArray($params, $this->merchant_key);

        return $this->getTxnStatusNew($this->getTransactionStatusUrl(), $params);

    }

}
