<?php

Broadcast::channel('App.User.{id}', function ($user, $id) {
	return (int) $user->id === (int) $id;
});

Broadcast::channel('App.Polo.{id}', function ($user, $id) {
	return (int) @$user->polo_id === (int) $id;
});

Broadcast::channel('App.Tenant.{id}', function ($user, $id) {
	return (int) @$user->tenant_id === (int) $id;
});




Broadcast::channel('Alert.User.{id}', function ($user, $id) {
	return (int) $user->id === (int) $id;
});

Broadcast::channel('Alert.Polo.{id}', function ($user, $id) {
	return (int) @$user->polo_id === (int) $id;
});

Broadcast::channel('Alert.Tenant.{id}', function ($user, $id) {
	return (int) @$user->tenant_id === (int) $id;
});
