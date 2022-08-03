<?php

Route::group(['middleware' => 'cors'], function () {
	require "api_integration.php";
	require "api_webhook.php";
	require "wpp.php";
});
