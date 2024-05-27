<?php

	namespace EasyAI\SEO\DataForSEO\Models\OnPage;

	use EasyAI\SEO\DataForSEO\DataForSEOClient;
	use EasyAI\Tools\Cleaner;

	use Exception;

	class RawHtml
	{
		public function __construct(
			private string $taskId,
			private string $url
		)
		{
			// Cleaner
			$this->cleaner = new Cleaner();
		}

		public function getHtml(): ?string
		{
			// Setup Client
			$client = new DataForSEOClient();

			// Post Parameter
			$post_array = array();
			$post_array[] = array(
				'id' => $this->taskId,
				'url' => $this->url,
			);
			try {
				// POST /v3/on_page/raw_html
				// the full list of possible parameters is available in documentation
				$result = $client->post('/v3/on_page/raw_html', $post_array);

				$html = $result['tasks'][0]['result'][0]['items']['html'] ?? null;
				if ($html) {
					return $this->cleaner->minifyHtml($html);
				}
				// Feature: Add ErrorHandling return or throw
				return json_encode($result);
			} catch (RestClientException $e) {
				$error[] = "HTTP code: {$e->getHttpCode()}";
				$error[] = "Error code: {$e->getCode()}";
				$error[] = "Message: {$e->getMessage()}";
				$error[] = $e->getTraceAsString();

				// Feature: Add ErrorHandling return or throw
				throw new Exception($error);
			}

			$client = null;
		}
	}