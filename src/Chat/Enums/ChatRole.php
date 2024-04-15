<?php

	namespace EasyAI\Chat\Enums;

	/**
	 * Enum representing the possible roles in a chat.
	 *
	 * @enum {string}
	 */
	enum ChatRole: string
	{
		case System = 'system';
		case User = 'user';
		case Assistant = 'assistant';
		case Function = 'function';
	}