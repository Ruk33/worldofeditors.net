<?php

function is_dev()
{
	if (getenv("DEV"))
		return true;

	return false;
}
