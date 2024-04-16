<?php

	declare(strict_types=1);

	namespace EasyAI\Embeddings;

	class Vector
	{
		public ?string $id = null;

		public ?string $namespace = null;

		public string $content;

		/** @var float[] */
		public array $embedding;

		//public ?array $metas; Feature for later to store more meta information
	}