# EmbeddingGenerator

#### :left\_right\_arrow: Usage of `EmbeddingGenerator\OpenAI`

**Explanation of Embedding**

An "embedding" is a vector representation of text that captures semantic meanings of the words or phrases. These vectors are generated by deep learning models and can be used in machine learning applications to compare texts, search for similar content, or feed into other machine learning models for tasks like classification or clustering. Each component of the vector represents a dimension of the text's meaning, allowing the model to understand and process text similarly to how humans do but in a numerical form.

Usage of EmbeddingGenerator\OpenAI This section covers the different classes available for generating embeddings using OpenAI models. Below is a table summarizing the key details of each available class.

| Class                          | Model                  | Embedding size |
| ------------------------------ | ---------------------- | -------------- |
| OpenAIADA002EmbeddingGenerator | text-embedding-ada-002 | 1536           |
| OpenAI3SmallEmbeddingGenerator | text-embedding-3-small | 1536           |
| OpenAI3LargeEmbeddingGenerator | text-embedding-3-large | 3072           |

Source: [https://platform.openai.com/docs/guides/embeddings](https://platform.openai.com/docs/guides/embeddings)

Using the right class to pick one of the models:

```php
use EasyAI\Embeddings\EmbeddingGenerator\OpenAI\OpenAI3SmallEmbeddingGenerator; // Using OpenAI3SmallEmbeddingGenerator

// Embedding
$embeddingGenerator = new OpenAI3SmallEmbeddingGenerator();
$embedding = $embeddingGenerator->embedText('Your text string to embed');
```
