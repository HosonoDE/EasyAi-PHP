<?php

	namespace EasyAI\VectorStores\Pinecone;

	use Exception;
	use EasyAI\Embeddings\Vector;
	use EasyAI\VectorStores\VectorStoreBase;
	use EasyAI\VectorStores\Pinecone\PineconeConfig;

	use \Probots\Pinecone\Client as Pinecone;

	use function getenv;

	class PineconeVectorStore extends VectorStoreBase
	{
		public $pinecone;

		public function __construct(?PineconeConfig $config = null)
		{
			if (!class_exists(Pinecone::class)) {
				throw new \RuntimeException('To use this functionality, you must install the package: `composer require probots-io/pinecone-php`.');
			}

			if (isset($config->apiKey)) {
				$apiKey = $config->apiKey;
			} elseif (getenv('PINECONE_API')) {
				/* Use getenv */
				$apiKey = getenv('PINECONE_API');
			} elseif (isset($_ENV['PINECONE_API'])) {
				/* Use $_ENV */
				$apiKey = $_ENV['PINECONE_API'];
			} else {
				throw new Exception('You have to provide a `PINECONE_API` env var to request Pinecone.');
			}

			if (isset($config->host)) {
				$host = $config->host;
			} elseif (getenv('PINECONE_HOST')) {
				/* Use getenv */
				$host = getenv('PINECONE_HOST');
			} elseif (isset($_ENV['PINECONE_HOST'])) {
				/* Use $_ENV */
				$host = $_ENV['PINECONE_HOST'];
			} else {
				throw new Exception('You have to provide a `PINECONE_HOST` env var to request Pinecone.');
			}

			$this->pinecone = new Pinecone($apiKey, $host);
		}

		public function addVector(Vector $vector): void
		{
			// Checks if id is existing
			// Later: Add Check if space or illegal char is found
			if(!isset($vector->id)) {
				// Create hash based on content
				$vector->id = hash('sha256', $vector->content); // Issue: If same content
			}

			// Checks if namespace is existing
			if(!isset($vector->namespace)) {
				// Use default namespace of pinecone
				// https://docs.pinecone.io/guides/indexes/using-namespaces#querying-a-namespace
				$vector->namespace = "";
			}

			// Checks if ID is existing
			$response = $this->pinecone->data()->vectors()->fetch([
				$vector->id
			]);

			// probots-io/pinecone-php is using use Saloon\Http\Response; so it's little bit differnt so it's $response->body()
			$foundVectors = json_decode($response->body());

			// If ID is existing update vector
			if (isset($foundVectors->vectors->{$vector->id}->id)) {
				// Update if Vector found
				$this->pinecone->data()->vectors()->update(
					id: $vector->id,
					values: $vector->embedding,
					setMetadata: [
						'content' => $vector->content
					],
				);
			} else {
				// Add a Vector if not found
				$this->pinecone->data()->vectors()->upsert(vectors: [
					'id' => $vector->id,
					'values' => $vector->embedding,
					'metadata' => [
						'content' => $vector->content,
					],
				], namespace: $vector->namespace);
			}
		}

		public function addVectors(array $vectors): void
		{
			foreach ($vectors as $vector) {
				$this->addVector($vector);
			}
		}

		public function deleteVector(Vector $vector): void
		{
			if(!isset($vector->id)) {
				throw new Exception('A `id` is required for deleting a vector');
			} else {
				$response = $this->pinecone->data()->vectors()->delete(
					ids: [$vector->id],
					namespace: $vector->namespace ?? ''
				);
			}
		}

		/**
		 * @throws Exception
		 */
		public function similaritySearch(array $embedding, int $k = 4, array $additionalArguments = []): array
		{
			if(!isset($embedding)) {
				throw new Exception('No embedding passed!');
			} else {
				$response = $this->pinecone->data()->vectors()->query(
					vector: $embedding,
					topK: $k,
					includeValues: $additionalArguments['includeValues'] ?? false,
					includeMetadata: $additionalArguments['includeMetadata'] ?? true,
					namespace: $additionalArguments["namespace"] ?? "", // Use default if not set
				);

				if ($response->successful()) {
					$json_result = json_decode($response->body(), true);
					return $json_result["matches"];
				} else {
					throw new Exception('Pinecone response error');
				}
			}
		}
	}