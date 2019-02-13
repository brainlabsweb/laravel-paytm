<p align="center">
<a href="https://brainlabsweb.com" target="_blank">
<img src="https://brainlabsweb.com/safari-pinned-tab.svg" width="150px">
</a>
</p>
<h1 align="center">
:moneybag: Laravel Paytm :moneybag:
</h1>

<p align="center">
[![Latest Stable Version](https://poser.pugx.org/brainlabsweb/laravel-paytm/v/stable)](https://packagist.org/packages/brainlabsweb/laravel-paytm)
[![Total Downloads](https://poser.pugx.org/brainlabsweb/laravel-paytm/downloads)](https://packagist.org/packages/brainlabsweb/laravel-paytm)
[![License](https://poser.pugx.org/brainlabsweb/laravel-paytm/license)](https://packagist.org/packages/brainlabsweb/laravel-paytm)
</p>

<p align="center">
This package allows you to integrate Paytm payment gateway into your laravel app.
For Paytm full documentation your can refer <a href="https://developer.paytm.com/docs/">here</a>
</p>

## :bulb: Installation

1. Install the package via Composer:

```sh
composer require brainlabsweb/laravel-paytm
```
The package will automatically register its service provider.

2. publish the configuration file
```sh
php artisan vendor:publish --provider="Brainlabsweb\Paytm\PaytmServiceProvider"
```

## Configuration 
**Note: For Laravel 5.5 and above you can skip the following steps**

In you `` config/app.php `` add these 
```
'providers' => [
    // Other service providers...
    Brainlabsweb\Patym\PatymServiceProvider::class,
],
```
Also under aliases
```
'aliases' => [
    // Other aliases
    'Paytm' => Brainlabsweb\Patym\Paytm::class,
],
```

### To get the paytm api urls

These urls will automatically direct to corresponding paytm sandbox, live modes 
based on the :muscle: **default** status set in paytm config file

```
paytm()->getTxnUrl(); // for charging  
paytm()->getTransactionStatusUrl(); // to know the status of the paytm transaction
paytm()->getRefundUrl(); // to inititate refund
paytm()->getRefundStatusUrl(); // to know the refund status
```

### in your view file

```
/**
* The below are mandatory fields
* optional fields MOBILE_NO, EMAIL
*/
$data = [
    'ORDER_ID'   => 'order_id',
    'TXT_AMOUNT' => '1',
    'CUST_ID'    => 'custid'
];
<form method="POST" action="{{ paytm()->getTxnUrl() }}">
    @foreach(\Brainlabsweb\Paytm\Paytm::prepare($data) as $key => value)
    <input type="hidden" name="{{ $key }}" value="{{ $value }}">   
    @endforeach
    
    <button type="submit">Pay</button>
</form>
OR

paytm()->prepare($data)
```

### Disable CSRF on Paytm Routes
Make sure all POST request handling routes of Paytm are not CSRF protected. 
For example
```
Route::post('paytm/verify','PaytmController@verify');
```
You can disable these in `` app/Http/Middleware/VerifyCsrfToken.php ``

```
protected $except = ['paytm/verify'];
```
### Once the payment is done in your controller
```
\Brainlabsweb\Paytm\Paytm::verify(); // returns true/false 

OR

paytm()->verify();
```

#### To get Paytm response status
```
\Brainlabsweb\Paytm\Paytm::response(); // returns paytm response array 

OR
                  
paytm()->response();                   
```

#### To know the transaction status
```
Make POST REQUEST with param $order_id

\Brainlabsweb\Paytm\Paytm::getTransactionStatus($order_id); // returns paytm response array

OR

paytm()->getTransactionStatus($order_id); 
```

#### To initiate refund 
```
$data = [
    'ORDERID'   => 'order_id',
    'REFID' => 'ref1', // should be unique everytime
    'TXNID'    => 'TXNID' // will get as response when made a transaction
    'REFUNDAMOUNT' => '1',
    'COMMENT' => 'SOME TEXT' // THIS IS OPTIONAL PARAMTER
];

\Brainlabsweb\Paytm\Paytm::refund($data); // returns paytm response array 

OR

paytm()->refund($data);
```


#### To know the refund status 
```
$data = [
    'ORDERID'   => 'order_id',
    'REFID' => 'ref1', // This is REFID for which refund status is being inquired
];

\Brainlabsweb\Paytm\Paytm::refundStatus($data); // returns paytm response array

OR

paytm()->refundStatus($data); 
```
<p align="center">
<h3>Done!!</h3>
</p>
