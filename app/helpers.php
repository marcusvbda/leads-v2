<?php

use App\Http\Models\Module;

if (!function_exists('formatPhoneNumber')) {
	function formatPhoneNumber($tel)
	{

		$tel = preg_replace('/[^0-9]/', '', str_replace("+55", "", $tel));
		$tam = strlen(preg_replace("/[^0-9]/", "", $tel));
		if ($tam == 11) {
			return "(" . substr($tel, 0, 2) . ") " . substr($tel, 2, 5) . "-" . substr($tel, 7, 11);
		}
		if ($tam == 10) {
			return "(" . substr($tel, 0, 2) . ") " . substr($tel, 2, 4) . "-" . substr($tel, 6, 10);
		}
	}
}

if (!function_exists('hasPermissionTo')) {
	function hasPermissionTo($permission)
	{
		if (!\Auth::check()) {
			return false;
		}
		$user = \Auth::user();
		if ($user->hasRole(["super-admin"])) {
			return true;
		}
		$permission = trim($permission);
		return $user->can($permission);
	}
}

if (!function_exists('getEnabledModuleToUser')) {
	function getEnabledModuleToUser($module)
	{
		$user = Auth::user();
		if (!$user) {
			return false;
		}
		$module = Module::where("slug", $module)->whereJsonContains("polo_ids", (string)$user->polo_id)->first();
		return $module;
	}
}

if (!function_exists('formatDate')) {
	function formatDate($date, $format = "d/m/Y - H:i:s")
	{
		if (!$date) {
			return null;
		}
		return @$date->format($format);
	}
}

if (!function_exists('setModelDataValue')) {
	function setModelDataValue($self, $field, $value)
	{
		$self->data = (object)array_merge(@$self->data ? (array) $self->data : [], [$field => $value]);
	}
}

if (!function_exists('getEnabledIcon')) {
	function getEnabledIcon($enabled = false)
	{
		$icons = [
			true => '🟢',
			false => '🔴',
			'loading' => '
			<div class="loading-ballls d-flex flex-row align-items-center justify-content-center mr-2">
				<div class="spinner-grow spinner-grow-sm text-muted mr-1" role="status">
					<span class="sr-only">Loading...</span>
				</div>
				<div class="spinner-grow spinner-grow-sm text-muted mr-1" role="status">
					<span class="sr-only">Loading...</span>
				</div>
				<div class="spinner-grow spinner-grow-sm text-muted mr-1" role="status">
					<span class="sr-only">Loading...</span>
				</div>
			</div>
			'
		];
		return @$icons[$enabled] ?? '🟡';
	}
}

if (!function_exists('snakeCaseToCamelCase')) {
	function snakeCaseToCamelCase($string, $capitalizeFirstCharacter = false)
	{
		$str = str_replace('-', '', ucwords($string, '-'));
		if (!$capitalizeFirstCharacter) {
			$str = lcfirst($str);
		}
		return $str;
	}
}

if (!function_exists('Obj2Array')) {
	function Obj2Array($oject)
	{
		$array = json_decode(json_encode($oject), true);
		return $array;
	}
}

if (!function_exists('abort')) {
	function abort($value)
	{
		return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $value);
	}
}

if (!function_exists('debug_log')) {
	function debug_log(string $path, string $message, $context = [])
	{
		@mkdir(storage_path("logs/" . $path, 0755, true));
		\Log::channel('debug')->debug("\{$path} $message", $context);
	}
}


if (!function_exists('process_template')) {
	function process_template(string $template, array $context)
	{
		foreach ($context as $key => $value) {
			if (is_string($value) || is_numeric($value)) {
				$template = str_replace('{{' . $key . '}}', $value, $template);
			}
		}
		return $template;
	}
}
