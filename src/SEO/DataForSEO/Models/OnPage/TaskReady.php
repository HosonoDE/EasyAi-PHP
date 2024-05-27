<?php

	namespace EasyAI\SEO\DataForSEO\Models\OnPage;

	use EasyAI\SEO\DataForSEO\DataForSEOClient;

	class TaskReady
	{
		public function __construct()
		{}

		public function getTasks(): array
		{
			// Setup Client
			$client = new DataForSEOClient();

			try {
				$result = array();
				// #1 - using this method you can get a list of completed tasks
				// GET /v3/on_page/tasks_ready
				$tasks_ready = $client->get('/v3/on_page/tasks_ready');
				// you can find the full list of the response codes here https://docs.dataforseo.com/v3/appendix/errors
				if (isset($tasks_ready['status_code']) AND $tasks_ready['status_code'] === 20000) {
					foreach ($tasks_ready['tasks'] as $task) {
						if (isset($task['result'])) {
							foreach ($task['result'] as $task_ready) {
								// #2 - using this method you can get results of each completed task
								// GET /v3/on_page/summary/$id
								if (isset($task_ready['id'])) {
									$post_array = array();
									// get Pages
									$id = $task_ready['id'];
									$post_array[] = array(
										"id" => "$id",
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
							}
						}
					}
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