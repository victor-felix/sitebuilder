<?php
namespace pushwoosh;

class Push
{
	public static function notify($title, $content, $devices)
	{
		return "Sending notification with title: $title and content: $content for devices: " . implode(',', $devices);
	}
}
