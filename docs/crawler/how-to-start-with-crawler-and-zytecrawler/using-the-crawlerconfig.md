---
description: This is optional, you can also work with the defaults.
---

# Using the CrawlerConfig

### **Using the `CrawlerConfig`**

#### Using `fallback`

When the standard crawler fails to access a site due to restrictions like cookies, JavaScript, or IP blocks, our method integrates a fallback mechanism. This approach incorporates the use of `ZyteCrawler` as an alternative. Leveraging both crawlers, we harness their combined strengths, ensuring reliable site access under various constraints.

```php
use EasyAI\CrawlerConfig;

$crawlerConfig = new CrawlerConfig();
$crawlerConfig->fallback = true; // default: false
```

```php
$crawler = new Crawler($crawlerConfig); // adding the config
$html = $crawler->getHtml('https://hosono.ai/en');
```

#### Using a different `Client`

If you want to use a differnt client you can also change it.

```php
use EasyAI\CrawlerConfig;
use Symfony\Contracts\HttpClient\HttpClientInterface;

function setupCrawler(HttpClientInterface $client) {
    $crawlerConfig = new CrawlerConfig();
    $crawlerConfig->client = $client; // default: GuzzleHttp
}
```

#### Using `errorHandlingStrategy`

If you want to use function calling for the AI, it will be important to return the error instead of throwing them, so the default for the `Crawler` error handling is `return`. If you want to throw an error, you can change it in the config.

```php
use EasyAI\CrawlerConfig;

$crawlerConfig = new CrawlerConfig();
$crawlerConfig->errorHandlingStrategy = "throw"; // default: return
```
