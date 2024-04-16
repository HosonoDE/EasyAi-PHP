<?php

	declare(strict_types=1);

	namespace EasyAI\VectorStores;

	use EasyAI\Embeddings\Vector;

	abstract class VectorStoreBase
	{
		abstract public function addVector(Vector $vector): void;
		
		abstract public function addVectors(array $vectors): void;

		abstract public function deleteVector(Vector $vector): void;

		/**
		 * Return docs most similar to the input embedding.
		 *
		 * @param  float[]  $embedding
		 * @param  array<string, string|int>|array<mixed[]>  $additionalArguments
		 * @return Vector[]
		 */
		abstract public function similaritySearch(array $embedding, int $k = 4, array $additionalArguments = []): array;
	}