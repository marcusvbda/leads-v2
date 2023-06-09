<?php

Route::group(['middleware' => 'cors'], function () {
	require "api_webhook.php";
});
