<?php

	namespace EasyAI\Tools;

	use GuzzleHttp\Client;
	use EasyAI\ZyteConfig;
	use EasyAI\Tools\Cleaner;

	use Exception;

	class ZyteCrawler
	{
		private readonly Client $client;
		private string $apiKey;
		private string $errorHandlingStrategy;

		public function __construct(
			?ZyteConfig $config = null
		)
		{
			// Construct Zyte Crawler
			if ($config instanceof ZyteConfig && $config->client instanceof Client) {
				// use ZyteConfig
				$this->clientZyte = $config->client;
			} else {
				// Assign API Key
				if (isset($config->apiKey)) {
					$this->apiKey = $config->apiKey;
				} elseif (getenv('ZYTE_API_KEY')) {
					/* Use getenv */
					$this->apiKey = getenv('ZYTE_API_KEY');
				} elseif (isset($_ENV['ZYTE_API_KEY'])) {
					/* Use $_ENV */
					$this->apiKey = $_ENV['ZYTE_API_KEY'];
				} else {
					throw new Exception('You have to provide a ZYTE_API_KEY env var to request Zyte API.');
				}

				// Use GuzzleHttp
				$this->clientZyte = new Client();


				// Error Handling return or throw?
				if (isset($config->errorHandlingStrategy)) {
					$this->errorHandlingStrategy = $config->errorHandlingStrategy;
				} else {
					/* Use return als default so no error throw */
					$this->errorHandlingStrategy = 'return';
				}
			}

			// Add Cleaner
			$this->cleaner = new Cleaner();
		}

		public function getTexts(array $urls): array
		{
			$texts = [];
			foreach ($urls as $url) {
				$texts[$url] = $this->getText($url);
			}
			return $texts;
		}

		public function getHtmls(array $urls): array
		{
			$texts = [];
			foreach ($urls as $url) {
				$texts[$url] = $this->getHtml($url);
			}
			return $texts;
		}

		/**
		 * Fetches and cleans HTML from a provided URL.
		 *
		 * @param string $url The URL from which to fetch the HTML.
		 *
		 * @return string Returns the cleaned HTML text. If an exception occurs and the error handling strategy
		 * is set to 'return', it will return the error message.
		 *
		 * @throws \Exception If an exception occurs and the error handling strategy is set
		 * to 'throw', it will throw the exception with its message.
		 */
		public function getText(string $url): string
		{
			try {
				// use getZyteHtml for HTML
				$html = $this->getHtml($url);
				return $this->cleaner->cleanHtml($html);
			} catch (Exception $e) {
				if ($this->errorHandlingStrategy == 'return') { // Return to send it to e.g. AI or save in DB
					return $e->getMessage();
				} elseif ($this->errorHandlingStrategy == 'throw') {
					throw new \Exception($e->getMessage());
				}
			}
		}

		/**
		 * Retrieves the HTML content of a given URL using the Zyte API.
		 *
		 * @param string $url The URL for which to retrieve the HTML content.
		 * @return string The HTML content of the URL.
		 * @throws \Exception If an error occurs and the error handling strategy is set to 'throw'.
		 */
		public function getHtml(string $url): string
		{
			// Zyte Config
			$zyteOptions = [
				'auth' => [$this->apiKey, ''],
				'headers' => ['Accept-Encoding' => 'gzip'],
				'json' => [
					'url' => $url,
					'httpResponseBody' => true,
				],
			];
			try {
				// Request Zyte API
				$response = $this->clientZyte->request('POST', 'https://api.zyte.com/v1/extract', $zyteOptions);
				$data = json_decode($response->getBody());
				$html = base64_decode($data->httpResponseBody);

				if ($html === false) {
					if ($this->errorHandlingStrategy == 'return') { // Return to send it to e.g. AI or save in DB
						return $e->getMessage();
					} elseif ($this->errorHandlingStrategy == 'throw') {
						throw new \Exception($e->getMessage());
					}
				} else {
					return $html;
				}
			} catch (\GuzzleHttp\Exception\RequestException $e) {
				// Catch GuzzleHttp Exection
				$statusCode = $e->getResponse()->getStatusCode();
				$errorMsg = $e->getResponse()->getBody()->getContents();
				$response = [
					'status' => 'error',
					'code' => $statusCode,
					'message' => $errorMsg
				];
				return json_encode($response);
				
			} catch (Exception $e) {
				if ($this->errorHandlingStrategy == 'return') { // Return to send it to e.g. AI or save in DB
					return $e->getMessage();
				} elseif ($this->errorHandlingStrategy == 'throw') {
					throw new \Exception($e->getMessage());
				}
			}
		}
	}