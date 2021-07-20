<?php

Route::group(['middleware' => 'cors'], function () {
	require "webhook.php";
});