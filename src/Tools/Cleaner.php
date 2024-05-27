<?php

	namespace EasyAI\Tools;

	class Cleaner
	{
		/**
		 * Cleans the HTML content by removing script and style tags, stripping tags, decoding entities, replacing newline, tab, and carriage return characters with periods,
		 * removing multiple consecutive spaces, and replacing multiple consecutive periods with a single period.
		 *
		 * @param string $html The HTML content to clean.
		 * @return string The cleaned HTML content.
		 */
		public function cleanHtml(string $html): string
		{
			$text = (string)preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
			$text = (string)preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $text);
			$text = strip_tags($text);
			$text = html_entity_decode($text);

			$search = ["\n", "\t", "\r"];
			$text = str_replace($search, '.', $text);

			$text = (string)preg_replace('/( )+/', ' ', $text);
			return (string)preg_replace('/((\.)|( \.))+/', '.', $text);
		}

		public function minifyHtml(string $html): string
		{
			$search = ["\n", "\t", "\r"];
			$html = str_replace($search, ' ', $html);

			// Remove multiple spaces.
			$html = preg_replace('/\s+/', ' ', $html);

			// Remove spaces between HTML tags.
			$html = preg_replace('/> </', '><', $html);

			return $html;
		}
	}