<?php

if(!function_exists('paytm')) {
    /**
     * paytm helper class
     * @return mixed
     */
    function paytm() {
        return app()->make('paytm');
    }
}
