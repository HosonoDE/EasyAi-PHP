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

Open-Source Projects Used
-------------------------
This project utilizes several community-driven packages to power its features:

*   [openai-php/client](https://github.com/openai-php/client): For interfacing with OpenAI APIs.
*   [pinecone-php](https://github.com/probots-io/pinecone-php): For integrating vector databases.
*   [BunnyWay/BunnyCDN.PHP.Storage](https://github.com/BunnyWay/BunnyCDN.PHP.Storage): For cheap HDD & SSD storange + CDN
*   [deepl-php](https://github.com/DeepLcom/deepl-php): For translation services.
*   [LLPhant](https://github.com/theodo-group/LLPhant): An inspiration for our repo

Getting Started
---------------

### Prerequisites

Ensure you have PHP 8.1 or higher installed on your system.

### Installation

Install the project using Composer (coming soon):
```
composer require hosonode/easyai-php
```

### Usage

Integrate the helpers easily into your project using:
```php
use HosonoDE\EasyAI-PHP\SpecificHelper;
```

No further configuration is needed to get started.

Roadmap
=======

Completed Features
------------------

*   --
*   --

In Progress
-----------

*   --
*   --

Planned Features
----------------
*   **OpenAI GPT-3.5 and GPT-4 Helpers**:
*   **Pinecone Vector Databases Helpers**:
*   **Embedding Helpers**: With OpenAI
*   **Multi-language Helpers**: With DeepL & GPT 3.5 + GPT 4
*   **BunnyCDn Helper**:
*   **Claude 3 (Opus, Sonnet & Haiku) Helpers**:
*   **Gemini 1.0 & 1.5 Helpers**:
*   **Zyte API Helper**:
*   **PageSpeed Insights API Helper**:

FAQ
===

### Q1: Why using EasyAi-PHP and not OpenAI PHP SDK

**A1:** If you are an experienced developer and want to use many features of OpenAI, the OpenAI PHP SDK might be right for you. However, if you want to make simple requests to the OpenAI interface with very few lines of code, our repo is the right one for you, as we have programmed many helpers that you can use many AI and PHP helpers to improve your project.
Many studies and research papers also show that you should use many different AI agents to do different tasks, each AI has its strengths, weaknesses, functions, etc.. Therefore, it would be very difficult to familiarize yourself with all AIs, which is why we offer a kind of AI framework for you here.

Contributing
------------

We highly encourage community contributions. Whether you're a developer using our helpers or someone looking to enhance the functionalities of EasyAI-PHP, your contributions are welcome. This collaborative effort benefits everyone involved.

Support
-------

For AI-related inquiries, contact our HosonoAI support at support@hosono.ai. For service-related questions, reach out to support@hosono.de. We offer support in German, English, Japanese, Russian, and Italian.

Sponsored by
------------
*   **HosonoDE**: An e-commerce agency serving mainly D-A-CH (Germany / Austria / Switzerland) customers.
*   **HosonoAI**: A platform that enables easy, code-free creation of texts for e-commerce and marketing.
