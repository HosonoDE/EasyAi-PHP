<?php

	namespace EasyAI\SEO\DataForSEO\Models\OnPage;

	use EasyAI\SEO\DataForSEO\DataForSEOClient;

	class Pages
	{
		public function __construct()
		{
		}

		public function getPages(
			string $taskId
		): array
		{
			// Setup Client
			$client = new DataForSEOClient();

			try {
				$result = array();

				if (isset($taskId)) {
					$post_array = array();
					// get Pages
					$post_array[] = array(
						'id' => "$taskId",
						'order_by' => ['url,asc'],
						'limit' => 1000, // 1.000 is max
						'offset' => 0
						/*'filters' => [
							['resource_type', '=', 'html'],
							'and',
							['meta.scripts_count', '>', 40]
						],*/

					);

					$result[] = $client->post('/v3/on_page/pages', $post_array);
				}
				return $result;
				// do something with result
			} catch (RestClientException $e) {
				$error[] = "HTTP code: {$e->getHttpCode()}";
				$error[] = "Error code: {$e->getCode()}";
				$error[] = "Message: {$e->getMessage()}";
				$error[] = $e->getTraceAsString();

				return $error;
			}
			$client = null;
		}
	}