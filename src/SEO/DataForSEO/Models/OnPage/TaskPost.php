<?php

	namespace EasyAI\SEO\DataForSEO\Models\OnPage;

	use EasyAI\SEO\DataForSEO\DataForSEOClient;

	class TaskPost
	{
		const DEFAULT_MAX_CRAWL_PAGES = 100;

		private ?string $domain = '';
		private ?int $max_crawl_pages = self::DEFAULT_MAX_CRAWL_PAGES;
		private ?string $pingback_url = '';

		public function __construct()
		{}

		public function setDomain(?string $domain): void
		{
			$this->domain = $domain ?? '';
		}

		public function getDomain(): ?string
		{
			return $this->domain;
		}

		public function setPingbackUrl(?string $pingback_url): void
		{
			$this->pingback_url = $pingback_url ?? '';
		}

		public function getPingbackUrl(): ?string
		{
			return $this->pingback_url;
		}

		public function setMaxCrawlPages(?int $max_crawl_pages): void
		{
			$this->max_crawl_pages = $max_crawl_pages ?? self::DEFAULT_MAX_CRAWL_PAGES;
		}

		public function getMaxCrawlPages(): ?int
		{
			return $this->max_crawl_pages;
		}

		public function postTask(): array
		{
			if (empty($this->getDomain())) {
				return ["Domain is not set. You need to call setDomain() before calling postTask()"];
			}

			// Setup Client
			$client = new DataForSEOClient();

			// Config
			$post_array = [];
			$settings = [
				'target' => $this->getDomain(),
				'max_crawl_pages' => $this->getMaxCrawlPages(),
				'load_resources' => false,
				'enable_javascript' => false,
				'store_raw_html' => true,
				'enable_content_parsing' => true,
				'support_cookies' => true,
				'disable_cookie_popup' => true,
				'allow_subdomains' => true,
				'enable_www_redirect_check' => true
			];
			if ( !empty($this->getPingbackUrl()) ) {
				$settings['pingback_url'] = $this->getPingbackUrl();
			}
			$post_array[] = $settings;

			// this example has a 2 elements, but in the case of large number of tasks - send up to 100 elements per POST request
			if (count($post_array) > 0) {
				try {
					// POST /v3/on_page/task_post
					// the full list of possible parameters is available in documentation
					$result = $client->post('/v3/on_page/task_post', $post_array);
					return $result;
				} catch (RestClientException $e) {
					$error[] = "HTTP code: {$e->getHttpCode()}";
					$error[] = "Error code: {$e->getCode()}";
					$error[] = "Message: {$e->getMessage()}";
					$error[] = $e->getTraceAsString();

					return $error;
				}
			}
			$client = null;
		}
	}