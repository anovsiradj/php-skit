<?php

namespace anovsiradj\skit;

use CURLFile;
use CurlHandle;

class CURL
{
	const TYPE_URLE = 'application/x-www-form-urlencoded';
	const TYPE_MPFD = 'multipart/form-data';
	const TYPE_JSON = 'application/json';
	const TYPE_TEXT = 'text/plain';

	private $stderr = null;

	public static $defaults = [
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_RETURNTRANSFER => true,
	];

	/**
	 * @var CurlHandle
	 */
	public $handle;

	public $prefix;
	public $url;
	public $data;

	public $reqHeaders = [];
	public $resHeaders = [];

	function __construct($prefix, $reqHeaders = [], $defaults = [])
	{
		$this->prefix = $prefix;
		$this->handle = curl_init();
		$this->reqHeaders = $reqHeaders;

		$defaults = static::$defaults + $defaults;
		foreach ($defaults as $k => $v) {
			$this->opt($k, $v);
		}
	}

	public function url($suffix, array $params = [])
	{
		$url = $this->prefix . $suffix . '?' . http_build_query($params);
		$this->url = $url;
		$this->opt(CURLOPT_URL, $url);
	}

	public function opt($key, $val)
	{
		curl_setopt($this->handle, $key, $val);
	}

	private function stderrOpen($file)
	{
		$this->stderrClose();
		$this->stderr = fopen($file, 'a+');
	}

	private function stderrClose()
	{
		if (isset($this->stderr)) {
			fclose($this->stderr);
		}
		$this->stderr = null;
	}

	private function stderr($file)
	{
		$this->stderrOpen($file);
		curl_setopt($this->handle, CURLOPT_VERBOSE, true);
		curl_setopt($this->handle, CURLOPT_STDERR, $this->stderr);
	}

	/**
	 * @link https://gist.github.com/iansltx/a6ed41d19852adf2e496#file-multipartfromstrings-php
	 * @todo https://github.com/robtimus/php-multipart
	 * @deprecated
	 **/
	public function multipart(array $params, $boundary = null)
	{
		if (empty($boundary)) {
			$boundary = md5(__FUNCTION__);
		}

		$delimiter = '-------------' . $boundary;

		$makeText = function ($key, $val) use ($delimiter) {
			return "--" . $delimiter . "\r\n"
				. 'Content-Disposition: form-data; name="' . $key . "\"\r\n\r\n"
				. $val . "\r\n";
		};
		$makeFile = function ($key, CURLFile $val) use ($delimiter) {
			$content = file_get_contents($val->getFilename());

			return "--" . $delimiter . "\r\n"
				. 'Content-Disposition: form-data; name="' . $key . '"; filename="' . $val->getPostFilename() . '"' . "\r\n\r\n"
				. $content . "\r\n";
		};

		$loop = function (array $params, $altKey, &$contents, &$loop) use ($makeText, $makeFile) {
			foreach ($params as $key => $val) {
				if (is_array($val)) {
					$loop($val, $key, $contents, $loop);
				} elseif ($val instanceof CURLFile) {
					$contents .= $makeFile($altKey ?? $key, $val);
				} else {
					$contents .= $makeText($altKey ?? $key, $val);
				}
			}
		};

		$contents = '';
		$loop($params, null, $contents, $loop);
		$contents .= "--" . $delimiter . "--\r\n";

		$type = static::TYPE_MPFD;
		$size = strlen($contents);

		$this->reqHeaders[] = "Content-Type: {$type}; boundary={$delimiter}";
		$this->reqHeaders[] = "Content-Length: {$size}";

		$this->opt(CURLOPT_POST, true);
		$this->opt(CURLOPT_POSTFIELDS, $contents);
	}

	public function post($params, $type = null)
	{
		$type ??= static::TYPE_URLE;

		if ($type === static::TYPE_MPFD) {
			return $this->multipart($params);
		}

		if ($type === static::TYPE_JSON) {
			$this->reqHeaders[] = "Content-Type: {$type}";
			$params = json_encode($params, JSON_THROW_ON_ERROR | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
		}

		$this->opt(CURLOPT_POST, true);
		$this->opt(CURLOPT_POSTFIELDS, $params);
	}

	public function exec($stderr = null, $stdout = null)
	{
		if ($stderr) {
			$this->stderr($stderr);
		}

		$this->opt(CURLOPT_HEADERFUNCTION, function ($handle, $old) {
			$len = strlen($old);

			$new = $old;
			$new = trim($new);
			if (empty($new)) {
				return $len;
			}
			array_push($this->resHeaders, $new);

			return $len;
		});

		$this->opt(CURLOPT_HTTPHEADER, $this->reqHeaders);
		$result = curl_exec($this->handle);

		if ($stdout) {
			file_put_contents($stdout, $result . str_repeat(PHP_EOL, 3), FILE_APPEND);
		}

		$this->data = $result;
	}

	public function info($k)
	{
		return curl_getinfo($this->handle, $k);
	}

	public function data()
	{
		$data = $this->data;
		$type = $this->info(CURLINFO_CONTENT_TYPE);
		if (str_starts_with($type, static::TYPE_JSON)) {
			$data = json_decode($data, JSON_THROW_ON_ERROR | JSON_OBJECT_AS_ARRAY);
		}
		return $data;
	}

	public function code()
	{
		return $this->info(CURLINFO_HTTP_CODE);
	}

	public function __destruct()
	{
		$this->stderrClose();

		if (isset($this->handle)) {
			curl_close($this->handle);
		}
	}
}
