<?php

	declare(strict_types=1);

	namespace EasyAI;

	class CrawlerConfig
	{
		public ?Client $client = null;

		/**
		 * Specifies the error handling strategy.
		 * It must be either 'return' or 'throw'.
		 *
		 * @var string
		 */
		public string $errorHandlingStrategy;

		public bool $fallback;

	}