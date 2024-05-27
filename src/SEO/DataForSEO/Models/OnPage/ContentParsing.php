<?php

	namespace EasyAI\SEO\DataForSEO\Models\OnPage;

	use EasyAI\SEO\DataForSEO\DataForSEOClient;

	use Exception;

	class ContentParsing
	{
		public function __construct(
		)
		{}

		public function getContent(string $taskId, string $url): ?string
		{
			// Setup Client
			$client = new DataForSEOClient();

			// Post Parameter
			$post_array = array();
			$post_array[] = array(
				'id' => $taskId,
				'url' => $url,
			);
			try {
				// POST /v3/on_page/content_parsing
				// the full list of possible parameters is available in documentation
				$result = $client->post('/v3/on_page/content_parsing', $post_array);

				$page_content = $result['tasks'][0]['result'][0]['items'][0]['page_content'] ?? null;

				// Feature: Add ErrorHandling return or throw
				return json_encode($page_content);
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