<?php

	namespace EasyAI\Chat\Enums;

	/**
	 * Represents the available chat models in OpenAI.
	 * You can find all models here: https://platform.openai.com/docs/models
	 */
	enum OpenAIChatModel
	{
		case Gpt35Turbo;
		case Gpt4;
		case Gpt4TurboPreview;
		case Gpt4Turbo;

		public function getModelName(): string
		{
			return match ($this) {
				OpenAIChatModel::Gpt35Turbo => 'gpt-3.5-turbo',
				/* Currently points to gpt-4-0613. See continuous model upgrades. */
				OpenAIChatModel::Gpt4 => 'gpt-4',
				/* GPT-4 Turbo preview model. Currently points to gpt-4-0125-preview. */
				OpenAIChatModel::Gpt4TurboPreview => 'gpt-4-turbo-preview',
				/* GPT-4 Turbo with Vision The latest GPT-4 Turbo model with vision capabilities. Vision requests can now use JSON mode and function calling. Currently points to gpt-4-turbo-2024-04-09. */
				OpenAiChatModel::Gpt4Turbo => 'gpt-4-turbo',
			};
		}
	}