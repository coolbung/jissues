<?php
/**
 * Part of the Joomla Tracker's Support Application
 *
 * @copyright  Copyright (C) 2012 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Support\View\Icons;

use JTracker\View\AbstractTrackerHtmlView;

/**
 * The icons view
 *
 * @since  1.0
 */
class IconsHtmlView extends AbstractTrackerHtmlView
{
	/**
	 * Method to render the view.
	 *
	 * @return  string  The rendered view.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function render()
	{
		// Glyph icons
		$lines = file(JPATH_THEMES . '/vendor/bootstrap/less/glyphicons.less');

		$icons = array();

		foreach ($lines as $line)
		{
			if (preg_match('/.(glyphicon-[a-z0-9\-]+)/', $line, $matches))
			{
				if ('icon-bar' == $matches[1])
				{
					continue;
				}

				$icons[] = $matches[1];
			}
		}

		$this->renderer->set('icons', array_unique($icons));

		// Font Awesome
		$lines = file(JPATH_THEMES . '/vendor/font-awesome/css/font-awesome.css');

		$icons = array();

		foreach ($lines as $line)
		{
			if (preg_match('/.(fa-[a-z0-9\-]+):before/', $line, $matches))
			{
				if ('icon-bar' == $matches[1])
				{
					continue;
				}

				$icons[] = $matches[1];
			}
		}

		$this->renderer->set('fa_icons', array_unique($icons));

		return parent::render();
	}
}
