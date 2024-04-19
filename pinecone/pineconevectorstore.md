# PineconeVectorStore

#### :open\_file\_folder: Usage of `PineconeVectorStore`

{% hint style="info" %}
To streamline your setup, our PHP repository recognizes Pinecone API key and host stored in two default locations: `getenv('PINECONE_API')` or `$_ENV['PINECONE_API']` / `getenv('PINECONE_HOST')` or `$_ENV['PINECONE_HOST']`. Simply add your Pinecone key to your ENV file, and no additional configuration is necessary. We also use $\_ENV because frameworks like Symfony typically disable getenv by default.

You can add or change both with the PineconeConfig class, so ENV is not mandatory.
{% endhint %}

The advantage of using Pinecone is that it offers a serverless package where you only pay for what you use. You don't need your own AWS, Google Cloud or Azure for this. This model is highly cost-effective as it eliminates overcapacity and unnecessary expenses.

* Serverless Landingpage: [https://www.pinecone.io/product/](https://www.pinecone.io/product/)
* Serverless pricing: [https://www.pinecone.io/pricing/](https://www.pinecone.io/pricing/)

To start using Pinecone in your projects, you need to integrate the pinecone-php repository. Install this repository using Composer by executing the following command:

```
composer require probots-io/pinecone-php
```

**Adding Vector `addVector`**

```php
// Vector Class
use EasyAI\Embeddings\Vector;
// Pinecone Vector Store
use EasyAI\VectorStores\Pinecone\PineconeVectorStore;

// OpenAI Embedding
use EasyAI\Embeddings\EmbeddingGenerator\OpenAI\OpenAI3LargeEmbeddingGenerator; // In this example I am using the OpenAI3LargeEmbeddingGenerator

// Text to embed
$text = "I need support for Shopware 6";

// Embedding
$embeddingGenerator = new OpenAI3LargeEmbeddingGenerator();
$embedding = $embeddingGenerator->embedText($text);

// Create new vector object
$vector = new Vector();
//$vector->id = "id_84723"; // Additional: If not set hash will be used for id
//$vector->namespace = "MyNamespace"; // Additional: If not set, pinecone Default namespace
$vector->content = $text; // Add text for meta info in pinecone
$vector->embedding = $embedding; // Add vector array to vector class

// Save vector in Pinecone
$vectorStore = new PineconeVectorStore();
$vectorStore->addVector($vector); // void
```

**Deleting Vector `deleteVector`**

```php
// Pinecone Vector Store
use EasyAI\VectorStores\Pinecone\PineconeVectorStore;

// Delete Vector
$vector = new Vector();
$vector->id = "id_84723";
//$vector->namespace = "test"; // Additional: If not set default

$vectorStore = new PineconeVectorStore();
$vectorStore->deleteVector($vector);
```

**Search Vector `similaritySearch`**

```php

// OpenAI Embedding
use EasyAI\Embeddings\EmbeddingGenerator\OpenAI\OpenAI3LargeEmbeddingGenerator;

// Pinecone Storage
use EasyAI\VectorStores\Pinecone\PineconeVectorStore;

$text = "E-Commerce Support";
$embeddingGenerator = new OpenAI3LargeEmbeddingGenerator();
$embedding = $embeddingGenerator->embedText($text);

// SimilaritySearch
$vectorStore = new PineconeVectorStore();
//$arguments["namespace"] = "MyNamespace"; // Additional: If you want to use a differnt namespace than the default
//$arguments["includeMetadata"] = false; // Additional: If want to remove the metas in you result
//$arguments["includeValues"] = true; // Additional: If you also want to get the vector
$response = $vectorStore->similaritySearch($embedding,10,$arguments);

/* The $response will look like this
[
  {
    "id": "faq_690_q",
    "score": 0.372145921,
    "values": []
  },
  {
    "id": "faq_677_q",
    "score": 0.356256276,
    "values": []
  },
  ...
]
*/

```
