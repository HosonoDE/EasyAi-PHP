<?php

	namespace EasyAI\SEO\DataForSEO\Models\KeywordsData;

	use EasyAI\SEO\DataForSEO\DataForSEOClient;

	class GoogleKeywordsForSiteLive
	{
		public function __construct(
			/**
			 * @var string
			 *
			 * domain or page
			 * required field
			 * the domain name of the target website or the url of the target page;
			 * note: to obtain keywords for the target website, use the target_type parameter
			 */
			private string $target,

			/**
			 * @var string
			 *
			 * full name of search engine location
			 * optional field
			 * if you do not indicate the location, you will receive worldwide results, i.e., for all available locations;
			 * if you use this field, you don’t need to specify location_code or location_coordinate
			 * you can receive the list of available locations of the search engine with their location_name by making a separate request to https://api.dataforseo.com/v3/keywords_data/google_ads/locations
			 * example:
			 * London,England,United Kingdom
			 */
			private string $location_name,

			/**
			 * @var string
			 *
			 * full name of search engine language
			 * optional field
			 * you can receive the list of available languages of the search engine with their language_name by making a separate request to https://api.dataforseo.com/v3/keywords_data/google_ads/languages
			 * example:
			 * English
			 */
			private string $language_name,
		)
		{

		}

		public function getResult(): array
		{
			// Setup Client
			$client = new DataForSEOClient();

			$post_array = array();
			// simple way to set a task
			$post_array[] = array(
				'location_name' => $this->location_name,
				'language_name' => $this->language_name,
				'target' => $this->target
			);
			try {
				// POST /v3/keywords_data/google_ads/keywords_for_site/live
				// the full list of possible parameters is available in documentation
				$result = $client->post('/v3/keywords_data/google_ads/keywords_for_site/live', $post_array);
				return $result;
			} catch (RestClientException $e) {
				$error[] = "HTTP code: {$e->getHttpCode()}";
				$error[] = "Error code: {$e->getCode()}";
				$error[] = "Message: {$e->getMessage()}";
				$error[] = $e->getTraceAsString();

				return $error;
			}
		}

		/**
		 * Retrieves test results from an external JSON API. Because every request costs around 7,5 Cents USD
		 *
		 * @return array The test results array.
		 */
		public function getTestResult(): array
		{
			$json = file_get_contents('https://hosonoai.b-cdn.net/inspector/dataForSEO/flower24-keyword.json');
			$data = json_decode($json, true);

			// `monthly_searches` remove monthly search because it's too much data
			foreach ($data['result'] as $key => $value) {
				unset($data['result'][$key]['monthly_searches']);
			}

			return $data;

		}
	}