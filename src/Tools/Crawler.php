<?php

	namespace EasyAI\Tools;

	use GuzzleHttp\Client;
	use EasyAI\CrawlerConfig;
	use EasyAI\Tools\Cleaner;

	// For fallback
	use EasyAI\Tools\ZyteCrawler;

	use Exception;

	class Crawler
	{
		private readonly Client $client;
		private string $apiKey;
		private string $errorHandlingStrategy;

		public function __construct(
			?CrawlerConfig $config = null
		)
		{
			// Construct vanilla Crawler
			if ($config instanceof CrawlerConfig && $config->client instanceof Client) {
				// use CrawlerConfig
				$this->client = $config->client;
			} else {
				// use GuzzleHttp if no Config with special Header
				$this->client = new Client([
					'headers' => [
						'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.96 Safari/537.36',
						'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
						'Accept-Language' => 'de-DE,de;q=0.9,en-US;q=0.8,en;q=0.7',
					]
				]);

				// Error Handling return or throw?
				if (isset($config->errorHandlingStrategy)) {
					$this->errorHandlingStrategy = $config->errorHandlingStrategy;
				} else {
					/* Use return als default so no error throw */
					$this->errorHandlingStrategy = 'return';
				}

				// Fallback strategy
				if (isset($config->fallback)) {
					$this->fallback = $config->fallback;
				} else {
					/* use no Zyte API as default */
					$this->fallback = false;
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
		 * Fetches the HTML from the provided URL, cleans it and returns the cleaned content.
		 *
		 * @param string $url The URL from which to fetch HTML content.
		 *
		 * @return string The cleaned contents of the HTML retrieved from URL.
		 *
		 * @throws Exception If there is any error during fetching, cleaning, or if the `errorHandlingStrategy` property is set to 'throw'.
		 */
		public function getText(string $url): string
		{
			try {
				// use getHtml for HTML 
				$html = $this->getHtml($url);
				return $this->cleaner->cleanHtml($html);
			} catch (Exception $e) {
				// Error Handling
				if ($this->errorHandlingStrategy == 'return') { // Return to send it to e.g. AI or save in DB
					return $e->getMessage();
				} elseif ($this->errorHandlingStrategy == 'throw') {
					throw new \Exception($e->getMessage());
				}
			}
		}

		/**
		 * Uses the Guzzle HTTP client to fetch the HTML content from the provided URL,
		 * and returns the HTML content or a error message depending on the `errorHandlingStrategy` property of the class.
		 *
		 * @param string $url The URL from which HTML contents are to be fetched.
		 *
		 * @throws Exception If there is an error fetching data and `errorHandlingStrategy` is set to 'throw'.
		 */
		public function getHtml(string $url): string
		{
			try {
				// Request Guzzle Client
				$response = $this->client->request("GET", $url, []); // Later maybe add also config for GET/POST & option
				$html = (string) $response->getBody();

				if ($html === false) {
					if ($this->errorHandlingStrategy == 'return') { // Return to send it to e.g. AI or save in DB
						return $e->getMessage();
					} elseif ($this->errorHandlingStrategy === 'throw') {
						throw new Exception($e->getMessage());
					}
				} else {
					return $html;
				}
			} catch (\GuzzleHttp\Exception\RequestException $e) {
				// if fallback is true, use Zyte API
				if ($this->fallback) {
					$zyteCrawler = new ZyteCrawler();
					return $zyteCrawler->getHtml($url);
				}

				// Catch GuzzleHttp Exection
				$statusCode = $e->getResponse()->getStatusCode();
				$errorMsg = $e->getResponse()->getBody()->getContents();
				$response = [
					'status' => 'error',
					'code' => $statusCode,
					'message' => $errorMsg
				];
				// Error Handling
				if ($this->errorHandlingStrategy == 'return') {  // Return to send it to e.g. AI or save in DB
					return json_encode($response);
				} elseif ($this->errorHandlingStrategy == 'throw') {
					throw new \Exception($errorMsg);
				}
				
			} catch (Exception $e) {
				// Error Handling
				if ($this->errorHandlingStrategy == 'return') {  // Return to send it to e.g. AI or save in DB
					return $e->getMessage();
				} elseif ($this->errorHandlingStrategy == 'throw') {
					throw new \Exception($e->getMessage());
				}
			}
		}
	}