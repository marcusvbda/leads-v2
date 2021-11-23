<?php

Route::group(['middleware' => 'cors'], function () {
	require "api_auth.php";
	require "api_webhook.php";
});
