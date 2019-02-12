<?php

namespace Brainlabsweb\Paytm\src\Traits;

trait PaytmCheckout
{

    /**
     * @param array $params
     * @return array
     * @throws PaytmException
     */
    public function prepare(array $params)
    {
        $defaults = [
            'MID' => $this->merchant_id,
            'CHANNEL_ID' => $this->channel,
            'WEBSITE' => $this->website,
            'INDUSTRY_TYPE_ID' => $this->industry_type,
            'CALLBACK_URL' => $this->callback_url
        ];

        $defaults = array_merge($defaults, $params);

        $this->validate($defaults, self::TXN_MANDATE_FIELDS);

        return array_merge($defaults, [
            'CHECKSUMHASH' => $this->getChecksumFromArray($defaults,$this->merchant_key)
        ]);
    }

    /**
     * @return bool
     * @throws PaytmException
     */
    public function verify()
    {
        if (!request('CHECKSUMHASH') && empty(request('CHECKSUMHASH'))) {
            throw new PaytmException('No CHECKSUMHASH found');
        }

        return $this->verifychecksum_e(request()->all(), $this->merchant_key, request('CHECKSUMHASH'));
    }

}
