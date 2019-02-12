<?php

namespace Brainlabsweb\Paytm\src\Traits;

trait PaytmRefundStatus
{
    /**
     * verify refund status
     * @param array $params
     * @return
     */
    public function refundStatus(array $params)
    {
        $defaults = [
            'MID' => $this->merchant_id,
        ];

        $defaults = array_merge($defaults,$params);

        $this->validate($defaults,self::REFUND_STATUS_MANDATE_FIELDS);

        $defaults = array_merge($defaults, [
            'CHECKSUM' => $this->getRefundChecksumFromArray($defaults,$this->merchant_key)
        ]);

        return $this->callAPI($this->getRefundStatusUrl(),$defaults);
    }
}
