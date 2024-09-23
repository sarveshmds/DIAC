<?php
$rules;
defined('BASEPATH') or exit('No direct script access allowed');
if (!function_exists('hasXSS')) {
	function hasXSS($inArray, $rule)
	{
		global $is_found, $rules, $temp_rule;
		if (count($rule) > 0) {
			$rules = $rule;
		} else {
			$rules = array("&lt", "&gt", "<", ">", ":", "=");
		}
		iterateArrayRecursively($inArray);
		return $is_found;
	}
}
/**
 * Recursive function to iterate members of array with indentation
 *
 * @param array $arr Array to process
 * @param string $indent indentation string
 */
function iterateArrayRecursively($arr)
{
	global $is_found;
	if ($arr) {
		foreach ($arr as $value) {
			if (is_array($value)) {
				iterateArrayRecursively($value);
			} else {
				if (validate($value)) {
					$is_found = true;
				}
			}
		}
	}
}

function validate($source)
{
	global $rules;
	$isFound = false;
	for ($i = 0; $i < count($rules); $i++) {
		if (strpos($source, $rules[$i]) !== false) {
			$isFound = true;
		}
	}
	return $isFound;
}
function generateToken($formName)
{
	$secretKey = 'gsfhs154aergz2#';
	if (!session_id()) {
		session_start();
	}
	$sessionId = session_id();
	return sha1($formName . $sessionId . $secretKey);
}
function checkToken($token, $formName)
{
	return $token === generateToken($formName);
}

function customURIEncode($data)
{
	return urldecode(base64_encode($data));
}

function customURIDecode($data)
{
	return base64_decode(urldecode($data));
}

function custom_xss_clean($data)
{
	$data = strip_tags($data);
	return $data;
}
