<?php
	namespace EasyAI\Tools;

	use GuzzleHttp\Client;
	use GuzzleHttp\Promise\Utils;

	class AsyncHttpRequests
	{
		private $client;

		public function __construct()
		{
			$this->client = new Client();
		}

		/**
		 * Retrieves responses from multiple requests.
		 *
		 * @param array $requests An array of requests.
		 *                       Each request should have a 'method' and 'url' key.
		 *                       The 'method' key is optional and defaults to 'GET'.
		 *
		 * @return array An array of responses.
		 *               Each response is either a decoded JSON array or an error message.
		 */
		public function getResponses(array $requests): array
		{
			$promises = [];
			$responses = [];
			try {
				foreach ($requests as $request) {
					$method = isset($request['method']) ? $request['method'] : 'GET';
					$promises[] = $this->client->requestAsync($method, $request['url']);
				}
				$results = Utils::settle($promises)->wait();

				foreach ($results as $result) {
					if ($result['state'] === 'fulfilled') {
						$responses[] = json_decode($result['value']->getBody()->getContents(), true);
					} else {
						// Exception-Object
						$responses[]["error"] = 'Error: '.$result['reason'];
					}
				}
			} catch (Exception $e) {
				// Other Excecption like JSON-Parsing-Error
				$responses[] = 'Error: '.$e->getMessage();
			}
			return $responses;
		}
	}