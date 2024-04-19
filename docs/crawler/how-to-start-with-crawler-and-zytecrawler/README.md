# How to start with Crawler & ZyteCrawler

## Utilizing Different Crawlers with EasyAI

When integrating web crawling functionality in your applications using EasyAI, you have the flexibility to choose between the basic `Crawler` and the more advanced `ZyteCrawler` that leverages the Zyte API for enhanced capabilities. Below are examples of how to work with both:

### **Using the `Crawler`**

```php
use EasyAI\Tools\Crawler;

$crawler = new Crawler();
```

#### To fetch the full HTML content of a webpage:

```php
// one URL
$html = $crawler->getHtml('https://hosono.ai/en');

// multiple URLs
$urls = [
    'https://hosono.ai/en',
    'https://hosono.ai/de'
];
$html = $crawler->getHtmls($urls);
```

#### Or, to retrieve only the textual content:

```php
// one URL
$text = $crawler->getText('https://hosono.ai/en');

// multiple URLs
$urls = [
    'https://hosono.ai/en',
    'https://hosono.ai/de'
];
$text = $crawler->getTexts($urls);
```

### **Using the `ZyteCrawler`**

The `ZyteCrawler` works similarly to the `Crawler` but offers access to the [Zyte API's](https://www.zyte.com/zyte-api/) advanced features. Depending on your specific requirements, you can choose the most suitable crawler for your project.

{% hint style="info" %}
To streamline your setup, our PHP repository recognizes OpenAI API keys stored in two default locations: `getenv('ZYTE_API_KEY')` or `$_ENV['ZYTE_API_KEY']`. Simply add your OpenAI key to your ENV file, and no additional configuration is necessary. We also use $\_ENV because frameworks like Symfony typically disable getenv by default.
{% endhint %}

```php
use EasyAI\Tools\ZyteCrawler;

$zyteCrawler = new ZyteCrawler();
```

#### To fetch the full HTML content of a webpage:

```php
// one URL
$html = $zyteCrawler->getHtml('https://hosono.ai/en');

// multiple URLs
$urls = [
    'https://hosono.ai/en',
    'https://hosono.ai/de'
];
$html = $zyteCrawler->getHtmls($urls);
```

#### Or, to retrieve only the textual content:

```php
// one URL
$text = $zyteCrawler->getText('https://hosono.ai/en');

// multiple URLs
$urls = [
    'https://hosono.ai/en',
    'https://hosono.ai/de'
];
$text = $zyteCrawler->getTexts($urls);
```
