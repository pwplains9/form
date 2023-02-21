<?php
class Mail
{
	/**
	 * @var string
	 */
	private $to;

	/**
	 * @var string
	 */
	private $from;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $subject;

	/**
	 * @var string
	 */
	private $message;
	/**
	 * @var null
	 */
	private $additionalHeaders;
	/**
	 * @var bool
	 */
	private $isHTML;

	/**
	 * @param string $to
	 * @param string $from
	 * @param string $name
	 * @param string $subject
	 * @param string $message
	 * @param array|null $headers
	 * @param bool $isHTML
	 */
	public function __construct($to, $from, $name, $subject = '', $message = '', $headers = null, $isHTML = true)
	{
		$this->to = $to;
		$this->from = $from;
		$this->name = $name;
		$this->subject = $subject;
		$this->message = $message;
		$this->additionalHeaders = $headers ? $headers : [];
		$this->isHTML = $isHTML;
	}

	/**
	 * @param string $subject
	 */
	public function setSubject($subject)
	{
		$this->subject = $subject;
	}

	/**
	 * @param string $message
	 */
	public function setMessage($message)
	{
		$this->message = $message;
	}

	/**
	 * @param array $additionalHeaders
	 */
	public function setAdditionalHeaders(array $additionalHeaders)
	{
		$this->additionalHeaders = $additionalHeaders;
	}

	/**
	 * @param bool $isHTML
	 */
	public function setHTML($isHTML)
	{
		$this->isHTML = $isHTML;
	}

	/**
	 * @return bool
	 */
	public function send()
	{
		$contentType = $this->isHTML ? 'text/html' : 'text/plain';
		$defaultHeaders = [
			"MIME-Version: 1.0",
			"Content-Type: $contentType; charset=utf-8",
			"From: $this->name <$this->from>",
		];
		$headers = implode("\r\n", array_merge($defaultHeaders, $this->additionalHeaders));

		return @mail($this->to, $this->subject, $this->message, $headers);
	}

	/**
	 * @param $files
	 * @return bool
	 */
	public function sendWithFile()
	{
		$separator = strtoupper(uniqid(time()));
		$contentType = $this->isHTML ? 'text/html' : 'text/plain';

		$defaultHeaders = [
			"MIME-Version: 1.0",
			"Content-Type: multipart/mixed; boundary=\"$separator\"",
			"From: $this->name <$this->from>",
		];

		$headers = implode("\r\n", array_merge($defaultHeaders, $this->additionalHeaders));
		$message = [
			"--$separator",
			"Content-Type: $contentType; charset=utf-8",
			"Content-Transfer-Encoding: 7bit",
			"",
			$this->message,
			"",
			"--$separator",
		];

		$name = $_FILES["FILE_DOC"]['name'];
		$path = $_FILES["FILE_DOC"]["tmp_name"]."/".$_FILES["FILE_DOC"]["name"];

		$uploaddir = '/var/www/tmp_files_dir/';
		$uploadfile = $uploaddir . basename($_FILES["FILE_DOC"]['name']);

		if (!move_uploaded_file($_FILES["FILE_DOC"]['tmp_name'], $uploadfile)) 
		{
			return false;
		}

		$handler = fopen($uploadfile, 'r');
		$content = fread($handler, filesize($uploadfile));
		fclose($handler);
		
		$message[] = implode("\r\n", [
			"Content-Type: application/octet-stream; name=\"$name\"",
			"Content-Transfer-Encoding: base64",
			"Content-Disposition: attachment",
			"",
			chunk_split(base64_encode($content)),
			"--$separator",
		]);

		$message = implode("\r\n", $message);

		$t = mail($this->to, $this->subject, $message, $headers);
		@unlink($uploadfile);

		return true;
	}
}