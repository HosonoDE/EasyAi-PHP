<?php

	namespace EasyAI\Tools;

	// https://symfony.com/doc/current/components/dom_crawler.html
	use Symfony\Component\DomCrawler\Crawler;

	class HtmlExtractor
	{
		/**
		 * @var bool
		 */
		private $includeImages;

		/**
		 * @var bool
		 */
		private $includeLinks;

		private $crawler;
		public function __construct(string $html) {
			// Check if Dom Crawler is existing
			if (!class_exists('Symfony\Component\DomCrawler\Crawler')) {
				throw new \Exception("Symfony DOM Crawler is not installed. Please install it with 'composer require symfony/dom-crawler symfony/css-selector'");
			}

			$this->crawler = new Crawler($html);

			// Set options
			$this->includeImages = true;
			$this->includeLinks = true;
		}

		public function setIncludeImages(bool $includeImages): self
		{
			$this->includeImages = $includeImages;
			return $this;
		}

		public function setIncludeLinks(bool $includeLinks): self
		{
			$this->includeLinks = $includeLinks;
			return $this;
		}

		public function extractText(string $selector): ?string
		{
			return $this->crawler->filter($selector)->text();
		}

		public function extractTexts(string $selector): ?array
		{
			$nodes = $this->crawler->filter($selector)->each(function (Crawler $node) {
				return $node->text();
			});
			return $nodes;
		}

		public function extractAttribute(string $selector, string $attribute): ?string
		{
			$extractedContent = $this->crawler->filter($selector);

			// Check if found
			if ($extractedContent->count() > 0) return $extractedContent->attr($attribute);
			return null;
		}

		public function extractAttributes(string $selector, string $attribute): ?array
		{
			$nodes = $this->crawler->filter($selector)->each(function (Crawler $node) use ($attribute) {
				return $node->attr($attribute);
			});
			return $nodes;
		}

		public function extractInnerHtml(string $selector): ?string
		{
			// Extract Innter HTML
			$extractedContent = $this->crawler->filter($selector);

			// Check if found
			/* TODO: Adding to other methods */
			if ($extractedContent->count() > 0) return $extractedContent->html();
			return null;
		}

		public function extractInnerHtmls(string $selector): ?array
		{
			$nodes = $this->crawler->filter($selector)->each(function (Crawler $node) {
				return $node->html();
			});
			return $nodes;
		}

		public function extractOuterHtml(string $selector): ?string
		{
			return $this->crawler->filter($selector)->outerHtml();
		}

		public function extractOuterHtmls(string $selector): ?array
		{
			$nodes = $this->crawler->filter($selector)->each(function (Crawler $node) {
				return $node->outerHtml();
			});
			return $nodes;
		}

		/* Special Cases */

		public function extractLink(): ?string
		{
			return $this->crawler->filter("a")->attr("href");
		}

		public function extractLinks(): ?array
		{
			$nodes = $this->crawler->filter("a")->each(function (Crawler $node) {
				return $node->attr("href");
			});
			return $nodes;
		}

		public function extractInternalLink(string $domain): ?string
		{
			// call the extractInternalLinks
			$internalLinks = $this->extractInternalLinks($domain);

			//select the first link
			return $internalLinks[0] ?? null;
		}

		public function extractInternalLinks(string $domain): ?array
		{
			$baseUrl = "https://{$domain}";
			$this->crawler->filter('a')->each(function (Crawler $node) use (&$nodes, $baseUrl) {
				$href = $node->attr('href');
				if(str_starts_with($href, $baseUrl) || !parse_url($href, PHP_URL_HOST)) {
					$nodes[] = str_replace($baseUrl, '', $href);
				}
			});
			
			return $nodes;
		}

		public function extractHeadlineHierarchy(): ?array
		{
			// Define an anonymous function for recursive look-up
			$getHeadlineHierarchyRecursive = function(Crawler $crawler, int $headlineLevel = 0) use (&$getHeadlineHierarchyRecursive): array {
				// Initialize output array
				$output = [];

				// Iterate over each child node of the given node
				foreach ($crawler->children() as $childNode) {
					// Create a new crawler instance for child node
					$childCrawler = new Crawler($childNode);
					// Get tag name of child node or null if not available
					$childTag = $childNode->tagName ?? null;
					// Check if the child node tag is a headline tag (h1-h6)
					if (preg_match('/^h([1-6])$/', $childTag, $matches)) {
						// If so, update current headline level and get headline text
						$headlineLevel = (int)$matches[1];
						$headlineText = $childCrawler->text();
						// Add headline details to output
						$output[] = ['tag' => "h".$headlineLevel, 'text' => $headlineText];
					}
					// Check if the child node has further child nodes
					if ($childNode->hasChildNodes()) {
						// If so, recursively apply function to the child node
						// and merge results into the output
						$output = array_merge($output, $getHeadlineHierarchyRecursive($childCrawler, $headlineLevel));
					}
				}
				return $output;
			};
			// Start recursion with the first node
			return $getHeadlineHierarchyRecursive($this->crawler, 0);
		}

		public function extractSeo(array $options = null): ?array
		{
			$selectors = [
				':not(svg) > title' => function ($node) { return $node->each(function($node) { return $node->text(); }); },
				// meta tags
				'meta[name=description]' => function ($node) { return $node->each(function($node) {return $node->attr('content'); }); },
				'meta[name=robots]' => function ($node) { return $node->each(function($node) {return $node->attr('content'); }); },
				'meta[name=viewport]' => function ($node) { return $node->each(function($node) {return $node->outerHtml(); }); },
				'meta[http-equiv="content-language"]' => function ($node) { return $node->each(function($node) {return $node->outerHtml(); }); },
				'meta[property="og:title"]' => function ($node) { return $node->each(function($node) {return $node->outerHtml(); }); },
				'meta[property="og:description"]' => function ($node) { return $node->each(function($node) {return $node->outerHtml(); }); },
				'meta[property="og:image"]' => function ($node) { return $node->each(function($node) {return $node->outerHtml(); }); },
				'meta[name="twitter:title"]' => function ($node) { return $node->each(function($node) {return $node->outerHtml(); }); },
				'meta[name="twitter:description"]' => function ($node) { return $node->each(function($node) {return $node->outerHtml(); }); },
				'meta[name="twitter:image"]' => function ($node) { return $node->each(function($node) {return $node->outerHtml(); }); },
				// links
				'link[rel=canonical]' => function ($node) { return $node->each(function($node) {return $node->attr('href'); }); },
				'link[rel=alternate][hreflang]' => function ($node) { return $node->each(function($node) {return $node->outerHtml(); }); },
				// head tag
				'script[type="application/ld+json"]' => function ($node) { return $node->each(function($node) {return $node->text(); }); },
				// h tags
				'h1' => function ($node) { return $node->each(function($node) {return $node->text(); }); },
				'h2' => function ($node) { return $node->each(function($node) {return $node->text(); }); },
				'h3' => function ($node) { return $node->each(function($node) {return $node->text(); }); },
				'h4' => function ($node) { return $node->each(function($node) {return $node->text(); }); },
				'h5' => function ($node) { return $node->each(function($node) {return $node->text(); }); },
				'h6' => function ($node) { return $node->each(function($node) {return $node->text(); }); },
				// img tag
				'img' => function ($node) { return $node->each(function($node) {return ['src' => $node->attr('src'), 'alt' => $node->attr('alt'), 'title' => $node->attr('title')]; }); },
				// a tag
				'a' => function ($node) { return $node->each(function($node) {return ['href' => $node->attr('href'), 'rel' => $node->attr('rel'), 'anchor' => $node->text()]; }); },
			];
			$result = [];
			foreach ($selectors as $selector => $callback) {
				try {
					$result[$selector] = $callback($this->crawler->filter($selector));
				} catch (\Exception $e) {
					$result[$selector] = null;
				}
			}

			// Remove img if no needed
			if (isset($this->includeImages) && $this->includeImages) {
				unset($result['img']);
			}

			// Remove link if no needed
			if (isset($this->includeLinks) && $this->includeLinks) {
				unset($result['a']);
			}

			// Add headline hierarchy
			$result["headline-hierarchy"] = $this->extractHeadlineHierarchy();

			return $result;
		}
	}