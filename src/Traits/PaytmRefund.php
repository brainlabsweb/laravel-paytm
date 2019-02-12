<?php

namespace Brainlabsweb\Paytm\src\Traits;

trait PaytmRefund
{
    /**
     * initiate refund
     * @param array $params
     * @return
     */
    public function refund(array $params)
    {
        $defaults = [
            'MID' => $this->merchant_id,
            'TXNTYPE' => 'REFUND',
        ];
        $defaults = array_merge($defaults,$params);

        $this->validate($defaults,self::REFUND_MANDATE_FIELDS);

        $defaults = array_merge($defaults, [
            'CHECKSUM' => $this->getRefundChecksumFromArray($defaults,$this->merchant_key)
        ]);

        return $this->callAPI($this->getRefundUrl(),$defaults);
    }

}
