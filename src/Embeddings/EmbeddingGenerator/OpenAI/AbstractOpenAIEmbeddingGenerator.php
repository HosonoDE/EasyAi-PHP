<?php

declare(strict_types=1);

namespace EasyAI\Embeddings\EmbeddingGenerator\OpenAI;

use Exception;
use EasyAI\Embeddings\Document;
use EasyAI\Embeddings\EmbeddingGenerator\EmbeddingGeneratorInterface;
use EasyAI\OpenAIConfig;
use OpenAI;
use OpenAI\Client;

use function getenv;
use function str_replace;

abstract class AbstractOpenAIEmbeddingGenerator implements EmbeddingGeneratorInterface
{
    public Client $client;

    /**
     * @throws Exception
     */
    public function __construct(?OpenAIConfig $config = null)
    {
        if ($config instanceof OpenAIConfig && $config->client instanceof Client) {
            $this->client = $config->client;
        } else {
			if (isset($config->apiKey)) {
				$apiKey = $config->apiKey;
			} elseif (getenv('OPENAI_API_KEY')) {
				/* Use getenv */
				$apiKey = getenv('OPENAI_API_KEY');
			} elseif (isset($_ENV['OPENAI_API_KEY'])) {
				/* Use $_ENV */
				$apiKey = $_ENV['OPENAI_API_KEY'];
			} else {
				throw new Exception('You have to provide a OPENAI_API_KEY env var to request OpenAI.');
			}

            $this->client = OpenAI::client($apiKey);
        }
    }

    /**
     * Call out to OpenAI's embedding endpoint.
     *
     * @return float[]
     */
    public function embedText(string $text): array
    {
        $text = str_replace("\n", ' ', $text);

        $response = $this->client->embeddings()->create([
            'model' => $this->getModelName(),
            'input' => $text,
        ]);

        return $response->embeddings[0]->embedding;
    }

    public function embedDocument(Document $document): Document
    {
        $text = $document->formattedContent ?? $document->content;
        $document->embedding = $this->embedText($text);

        return $document;
    }

    /**
     * @param  Document[]  $documents
     * @return Document[]
     */
    public function embedDocuments(array $documents): array
    {
        $embedDocuments = [];
        foreach ($documents as $document) {
            $embedDocuments[] = $this->embedDocument($document);
        }

        return $embedDocuments;
    }

    abstract public function getEmbeddingLength(): int;

    abstract public function getModelName(): string;
}
