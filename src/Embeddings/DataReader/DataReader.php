<?php

namespace EasyAI\Embeddings\DataReader;

use EasyAI\Embeddings\Document;

interface DataReader
{
    /**
     * @return Document[]
     */
    public function getDocuments(): array;
}
