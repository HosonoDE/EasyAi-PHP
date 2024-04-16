<?php

namespace EasyAI\Embeddings\EmbeddingGenerator;

use EasyAI\Embeddings\Document;

interface EmbeddingGeneratorInterface
{
    /**
     * @return float[]
     */
    public function embedText(string $text): array;

    public function embedDocument(Document $document): Document;

    /**
     * @param  Document[]  $documents
     * @return Document[]
     */
    public function embedDocuments(array $documents): array;

    public function getEmbeddingLength(): int;
}
