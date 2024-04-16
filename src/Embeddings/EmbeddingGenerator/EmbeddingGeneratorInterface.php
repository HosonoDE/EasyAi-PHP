<?php

	namespace EasyAI\Embeddings\EmbeddingGenerator;

	interface EmbeddingGeneratorInterface
	{
		/**
		 * @return float[]
		 */
		public function embedText(string $text): array;

		public function getEmbeddingLength(): int;
	}
