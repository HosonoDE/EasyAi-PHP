<?php

	namespace EasyAI\Tools;

	use EasyAI\Tools\HtmlExtractor;

	class SiteTypeClassifier
	{

		/**
		 * Classifies the type of site based on the provided HTML.
		 *
		 * @param string $html The HTML content of the website.
		 * @return string|null The classified site type, or null if it cannot be determined.
		 */
		public function classifySiteType(string $html): ?string
		{
			// Extract contents
			$extractor = new HtmlExtractor($html);
			$title = $extractor->extractInnerHtml('title');
			$canonical = $extractor->extractAttribute('link[rel=canonical]', 'href');
			$bodyClass = $extractor->extractAttribute('body', 'class');

			/**
			 * TODO: P0: Check URL structure (Shopify)
			 */


			/**
			 * P1: Body Class Checks
			 */
			$bodyClassMatch = match (true) {
				// Shopware 6
				str_contains($bodyClass, 'is-act-home') => 'home',
				str_contains($bodyClass, 'is-ctl-product') => 'product-detail',

				// Shopware 5
				str_contains($bodyClass, 'is--ctl-index') => 'home',
				str_contains($bodyClass, 'is--ctl-detail') => 'product-detail',

				// False to run the other checks
				default => false,
			};
			if($bodyClassMatch !== false){
				return $bodyClassMatch;
			}

			/**
			 * P2: SW6 Class Checking
			 */
			$sw6ProducListing = $extractor->extractAttributes("div[class*='cms-block-product-listing']", 'class');
			if(!empty($sw6ProducListing)){
				return "category";
			}

			/**
			 * P2: SW5 Class Checking
			 */
			$sw5ProducListing = $extractor->extractAttributes("div[class*=listing--content] .listing div", 'class');
			if (!empty($sw5ProducListing)) {
				return 'category';
			}

			/**
			 * P3: Legal site check
			 */
			$legalStrings = [
				'widerruf', 'impressum', 'datenschutz', 'agb', 'streitbeilegung', 'versand', 'zahlung', // DE
				'disclaimer', 'imprint', 'privacy', 'terms', 'terms-of-service', 'terms-and-conditions', 'agreement', 'shipping', 'payment', // EN
				'déni', 'empreinte', 'confidentialité', 'expédition', 'livraison', 'paiement', // FR
			];
			foreach ($legalStrings as $legalString) {
				if (str_contains(strtolower($title), $legalString) || str_contains(strtolower($canonical), $legalString)) {
					return 'legal';
				}
			}

			/**
			 * P3: Blog / News / etc. site check
			 */
			$blogStrings = [
				'blog/', 'blog-',
				'news/', 'news-',
				'ratgeber',
				'wissen/', 'wissen-',
				'wissenswert/', 'wissenswert-',
				'magazin/', 'magazin-',
			];
			foreach ($blogStrings as $blogString) {
				if (str_contains(strtolower($title), $blogString) || str_contains(strtolower($canonical), $blogString)) {
					return 'blog';
				}
			}

			/**
			 * P3: About us etc. site check
			 */
			$aboutStrings = [
				'about/', 'about-',
				'ueber/', 'ueber-',
				'über/', 'über-',
				'team/', 'team-',
			];
			foreach ($aboutStrings as $aboutString) {
				if (str_contains(strtolower($title), $aboutString) || str_contains(strtolower($canonical), $aboutString)) {
					return 'about';
				}
			}

			/**
			 * P3: Contact or Contact Form check
			 */
			$contactStrings = [
				'kontakt', 'anfrage', 'kommunizieren', 'telefonische-', 'hilfe/', 'hilfe-', // DE
				'contact', 'enquiry', 'communicate', 'help/', 'help-', // EN
				'support',
			];
			foreach ($contactStrings as $contactString) {
				if (str_contains(strtolower($title), $contactString) || str_contains(strtolower($canonical), $contactString)) {
					return 'contact';
				}
			}

			/**
			 * P4: Body Class Checks
			 * If nothing found so far, check if these classess and output page
			 */
			$bodyClassMatch = match (true) {
				// Shopware 6
				str_contains($bodyClass, 'is-ctl-navigation is-act-index') => 'page',

				// Shopware 5
				str_contains($bodyClass, 'is--ctl-custom') => 'page',

				// False to run the other checks
				default => false,
			};
			if($bodyClassMatch !== false){
				return $bodyClassMatch;
			}

			// If nothing found
			return "unknown";
		}
	}