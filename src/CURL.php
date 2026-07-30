<?php

/**
 * Tujuan: Wrapper OO kecil untuk cURL (set URL, headers, POST, capture stdout/stderr).
 * Cara pakai: $curl = new CURL($prefix, $headers); $curl->url('/path'); $curl->post($data); $curl->exec().
 * Dependency: ext-curl.
 * Catatan standalone: Direkomendasikan via Composer autoload; bisa juga require file ini langsung.
 * 
 * @source kt_ifm_gtid_web,rental_web,intanbanjar_portal_web.
 * 
 * @link https://www.php.net/manual/en/function.curl-close.php
 */

namespace anovsiradj\skit;

use CURLFile;
use CurlHandle;

class CURL
{
	const TYPE_URLE = 'application/x-www-form-urlencoded';
	const TYPE_MPFD = 'multipart/form-data';
	const TYPE_JSON = 'application/json';
	const TYPE_TEXT = 'text/plain';

	public static $fileMimes = [
		'txt'  => 'text/plain',
		'html' => 'text/html',
		'htm'  => 'text/html',
		'css'  => 'text/css',
		'js'   => 'application/javascript',
		'json' => 'application/json',
		'xml'  => 'application/xml',
		'pdf'  => 'application/pdf',
		'jpg'  => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'png'  => 'image/png',
		'gif'  => 'image/gif',
		'svg'  => 'image/svg+xml',
		'webp' => 'image/webp',
		'zip'  => 'application/zip',
		'csv'  => 'text/csv',
		'xls'  => 'application/vnd.ms-excel',
		'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'doc'  => 'application/msword',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'mp3'  => 'audio/mpeg',
		'mp4'  => 'video/mp4',
		'wav'  => 'audio/wav',
	];

	private $stderr = null;

	public static $defaults = [
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_RETURNTRANSFER => true,
	];

	/**
	 * @var CurlHandle|resource|null
	 */
	public $handle;

	public $prefix;
	public $url;
	public $data;
	public $error;
	public $errno;

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
		$url = $this->prefix . $suffix;
		if ($params) {
			$url .= '?' . http_build_query($params);
		}
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
	 * Manual multipart body builder.
	 *
	 * NOTE: Not needed in practice — cURL auto-switches to multipart/form-data
	 * when CURLFile objects are present in CURLOPT_POSTFIELDS array.
	 *
	 * @link     https://gist.github.com/iansltx/a6ed41d19852adf2e496#file-multipartfromstrings-php
	 * @link     https://github.com/robtimus/php-multipart
	 * @internal
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

	public function nonHtmlFormMethod($method, $params, $type = null)
	{
		$type ??= static::TYPE_JSON;

		if ($type === static::TYPE_JSON) {
			$this->reqHeaders[] = "Content-Type: {$type}";
			$params = json_encode($params, JSON_THROW_ON_ERROR | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
		}

		$this->opt(CURLOPT_POSTFIELDS, $params);
		$this->opt(CURLOPT_CUSTOMREQUEST, $method);
	}

	public function put($params, $type = null)
	{
		$this->nonHtmlFormMethod('PUT', $params, $type);
	}

	public function patch($params, $type = null)
	{
		$this->nonHtmlFormMethod('PATCH', $params, $type);
	}

	public function delete($params = null, $type = null)
	{
		if (isset($params)) {
			$type ??= static::TYPE_URLE;

			if ($type === static::TYPE_JSON) {
				$this->reqHeaders[] = "Content-Type: {$type}";
				$params = json_encode($params, JSON_THROW_ON_ERROR | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
			}

			$this->opt(CURLOPT_POSTFIELDS, $params);
		}

		$this->opt(CURLOPT_CUSTOMREQUEST, 'DELETE');
	}

	public static function file(string $path, ?string $mime = null, ?string $postname = null): CURLFile
	{
		if (empty($mime) && function_exists('mime_content_type')) {
			$mime = mime_content_type($path);
		}
		if (empty($mime)) {
			$mime = static::fileMimeFromExt($path);
		}

		if (empty($postname)) {
			$postname = basename($path);
		}

		return new CURLFile($path, $mime, $postname);
	}

	private static function fileMimeFromExt(string $path): string
	{
		$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
		return static::$fileMimes[$ext] ?? 'application/octet-stream';
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

		if ($result === false) {
			$this->errno = curl_errno($this->handle);
			$this->error = curl_error($this->handle);
		}

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

		if (version_compare(PHP_VERSION, '8.0.0', '<') && isset($this->handle)) {
			curl_close($this->handle);
		}
	}
}
