# OpenAIChat

#### :speech\_balloon: Usage of `OpenAIChat`

{% hint style="info" %}
To streamline your setup, our PHP repository recognizes OpenAI API keys stored in two default locations: getenv('OPENAI\_API\_KEY') or $\_ENV\['OPENAI\_API\_KEY']. Simply add your OpenAI key to your ENV file, and no additional configuration is necessary. We also use $\_ENV because frameworks like Symfony typically disable getenv by default.
{% endhint %}

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

Here you can find out which exact model is currently being used. [https://platform.openai.com/docs/models/continuous-model-upgrades](https://platform.openai.com/docs/models/continuous-model-upgrades)
