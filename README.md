EasyAI-PHP
==========

About The Project
-----------------

EasyAI-PHP is an open-source initiative designed to significantly simplify the entry into artificial intelligence for PHP-developers. Based on PHP 8.1+, this project integrates advanced AI models and utilities, allowing developers to incorporate complex AI functionalities with minimal coding.

Key Features
------------

*   **Multiple AI Models**: Incorporates OpenAI's GPT-3.5, GPT-4, and Claude 3 (Opus, Sonnet & Haiku), along with Gemini 1.0 & 1.5 models.
*   **AI Utilities**: Offers a variety of helpers such as vector databases from Pinecone and embeddings through OpenAI, streamlining the integration of advanced AI features into your projects.
*   **Minimal Coding Requirement**: Designed to minimize the coding effort needed on your part, thanks to the numerous ready-to-use helpers.

Getting Started
---------------

Install the project using Composer:
```
composer require hosonode/easyai-php dev-master
```

Usage
---------------

### :speech_balloon: Usage of `OpenAIChat`
> [!NOTE]
> To streamline your setup, our PHP repository recognizes OpenAI API keys stored in two default locations: `getenv('OPENAI_API_KEY')` or `$_ENV['OPENAI_API_KEY']`. Simply add your OpenAI key to your ENV file, and no additional configuration is necessary. We also use `$_ENV` because frameworks like Symfony typically disable `getenv` by default.

If you want to use the default model, this is enough, so not a lof ot code:
```php
use EasyAI\Chat\OpenAIChat; // Using OpenAI Chat

// here is the code to call OpenAI API
$chat = new OpenAIChat();
$response = $chat->generateText('What is the population of Tokyo area, answer with only a number.');
// $response = "37 million"
```

If you want to select a specific modal, you need to use the Config & Enums:
```php
use EasyAI\OpenAIConfig; // Using OpenAI Config
use EasyAI\Chat\OpenAIChat; // Using OpenAI Chat
use EasyAI\Chat\Enums\OpenAIChatModel; // Using Enmus OpenAIChatModel

// e.g. in dependencyInjection
$config = new OpenAIConfig();
$config->apiKey = $_ENV['OTHER_KEY']; // Here you can define your key (as default we are using getenv('OPENAI_API_KEY') or $_ENV['OPENAI_API_KEY'])
$config->model = OpenAIChatModel::Gpt35Turbo->getModelName(); // Here you can define the model
$chat = new OpenAIChat($config);

// here is the code to call OpenAI API
$response = $chat->generateText('What is the population of Tokyo area, answer with only a number.');
// $response = "37 million"
```

Right now you can choose these enums:
```php
/* Currently points to gpt-3.5-turbo-0125. */
OpenAIChatModel::Gpt35Turbo => 'gpt-3.5-turbo',

/* Currently points to gpt-4-0613. See continuous model upgrades. */
OpenAIChatModel::Gpt4 => 'gpt-4',

/* GPT-4 Turbo preview model. Currently points to gpt-4-0125-preview. */
OpenAIChatModel::Gpt4TurboPreview => 'gpt-4-turbo-preview',

/* Currently points to gpt-4-turbo-2024-04-09. */
OpenAiChatModel::Gpt4Turbo => 'gpt-4-turbo',
```
Here you can find out which exact model is currently being used.
[https://platform.openai.com/docs/models/continuous-model-upgrades](https://platform.openai.com/docs/models/continuous-model-upgrades)

### :left_right_arrow: Usage of `EmbeddingGenerator\OpenAI`

#### Explanation of Embedding
An "embedding" is a vector representation of text that captures semantic meanings of the words or phrases. These vectors are generated by deep learning models and can be used in machine learning applications to compare texts, search for similar content, or feed into other machine learning models for tasks like classification or clustering. Each component of the vector represents a dimension of the text's meaning, allowing the model to understand and process text similarly to how humans do but in a numerical form.

Usage of EmbeddingGenerator\OpenAI
This section covers the different classes available for generating embeddings using OpenAI models. Below is a table summarizing the key details of each available class.

| Class | Model | Embedding size |
| --- | --- | --- |
| OpenAIADA002EmbeddingGenerator | text-embedding-ada-002 | 1536 |
| OpenAI3SmallEmbeddingGenerator | text-embedding-3-small | 1536 |
| OpenAI3LargeEmbeddingGenerator | text-embedding-3-large | 3072 |

Source: [https://platform.openai.com/docs/guides/embeddings](https://platform.openai.com/docs/guides/embeddings)

Using the right class to pick one of the models:
```php
use EasyAI\Embeddings\EmbeddingGenerator\OpenAI\OpenAI3SmallEmbeddingGenerator; // Using OpenAI3SmallEmbeddingGenerator

// Embedding
$embeddingGenerator = new OpenAI3SmallEmbeddingGenerator();
$embedding = $embeddingGenerator->embedText('Your text string to embed');
```

### :open_file_folder: Usage of `PineconeVectorStore`

> [!NOTE]
> To streamline your setup, our PHP repository recognizes Pinecone API key and host stored in two default locations: `getenv('PINECONE_API')` or `$_ENV['PINECONE_API']` / `getenv('PINECONE_HOST')` or `$_ENV['PINECONE_HOST']`. Simply add your Pinecone key to your ENV file, and no additional configuration is necessary. We also use `$_ENV` because frameworks like Symfony typically disable `getenv` by default.
> 
> You can add or change both with the PineconeConfig class, so ENV is not mandatory.

The advantage of using Pinecone is that it offers a serverless package where you only pay for what you use. You don't need your own AWS, Google Cloud or Azure for this. This model is highly cost-effective as it eliminates overcapacity and unnecessary expenses.

- Serverless Landingpage: [https://www.pinecone.io/product/](https://www.pinecone.io/product/)
- Serverless pricing: [https://www.pinecone.io/pricing/](https://www.pinecone.io/pricing/)

To start using Pinecone in your projects, you need to integrate the pinecone-php repository. Install this repository using Composer by executing the following command:
```
composer require probots-io/pinecone-php
```

#### Adding Vector `addVector`
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

#### Deleting Vector `deleteVector`
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

#### Search Vector `similaritySearch`
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

:world_map: Roadmap
=======

:heavy_check_mark: Completed Features:
------------------

- [x] **OpenAI GPT-3.5 and GPT-4 Helpers**:
- [x] **Embedding Helpers**: With OpenAI
- [x] **Pinecone Vector Databases Add**: Add Vector/s Delete them
- [x] **Pinecone Vector Databases Search**: Semantic Search

:construction: In Progress:
-----------

- [ ] P1: **Claude 3 (Opus, Sonnet & Haiku) Helpers**:

:rocket: Planned Features:
----------------
- [ ] P1: **Embedding: AI Text Chunking**: With GPT3.5 or Claude 3 Haiku or Gimini 1.0
- [ ] P1: **Embedding: Text Chunking**: By separators and limits
- [ ] P1: **BunnyCDN**: Upload big files or text to CDN
- [ ] P1: **Gemini 1.0 & 1.5**: Text Generation
- [ ] P2: **Gemini 1.0 & 1.5**: Vision Support
- [ ] P3: **Gemini 1.0 & 1.5**: Full Multimodal support
- [ ] P1: **Claude 3 (Opus, Sonnet & Heiku)**: Text Generation
- [ ] P2: **Claude 3 (Opus, Sonnet & Heiku)**: Vision Support
- [ ] P1: **Zyte API**: With Guzzle as beginner
- [ ] P1: **DOM Crawling**: With Symfony DOM Crawler
- [ ] P1: **PageSpeed Insights API**:
- [ ] P2: **DALL·E 3**: Image Creationg
- [ ] P3: **AI Translator**: With DeepL
- [ ] P3: **AI Translator**: With GPT 3.5 & GPT 4
- [ ] P3: **AI Translator**: With Claude 3 (Opus, Sonnet & Heiku)
- [ ] P3: **AI Translator**: With Gemini 1.0 & 1.5
- [ ] P3: **Tokenizer for PHP**: With https://github.com/yethee/tiktoken-php
- [ ] P3: **OpenAI Whisper**: Audio Support
- [ ] P3: **Example: Pinecone semantic search tool**: UI
- [ ] P3: **Example: AI Colosseum**: Test differnt AIs with same input

FAQ
===

### Q1: Why using EasyAi-PHP and not OpenAI PHP SDK

**A1:** If you are an experienced developer and want to use many features of OpenAI, the OpenAI PHP SDK might be right for you. However, if you want to make simple requests to the OpenAI interface with very few lines of code, our repo is the right one for you, as we have programmed many helpers that you can use many AI and PHP helpers to improve your project.
Many studies and research papers also show that you should use many different AI agents to do different tasks, each AI has its strengths, weaknesses, functions, etc.. Therefore, it would be very difficult to familiarize yourself with all AIs, which is why we offer a kind of AI framework for you here.

Contributing
------------

We highly encourage community contributions. Whether you're a developer using our helpers or someone looking to enhance the functionalities of EasyAI-PHP, your contributions are welcome. This collaborative effort benefits everyone involved.

Open-Source Projects Used
-------------------------
This project utilizes several community-driven packages to power its features:

*   [openai-php/client](https://github.com/openai-php/client): For interfacing with OpenAI APIs.
*   [pinecone-php](https://github.com/probots-io/pinecone-php): For integrating vector databases.
*   [BunnyWay/BunnyCDN.PHP.Storage](https://github.com/BunnyWay/BunnyCDN.PHP.Storage): For cheap HDD & SSD storange + CDN
*   [deepl-php](https://github.com/DeepLcom/deepl-php): For translation services.
*   [LLPhant](https://github.com/theodo-group/LLPhant): An inspiration for our repo

Support
-------

For AI-related inquiries, contact our HosonoAI support at support@hosono.ai. For service-related questions, reach out to support@hosono.de. We offer support in German, English, Japanese, Russian, and Italian.

Sponsored by
------------
*   **HosonoDE**: An e-commerce agency serving mainly D-A-CH (Germany / Austria / Switzerland) customers.
*   **HosonoAI**: A platform that enables easy, code-free creation of texts for e-commerce and marketing.
