<?php
/**
 * Part of the Joomla Tracker Model Package
 *
 * @copyright  Copyright (C) 2012 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace JTracker\Model;

use App\Projects\TrackerProject;

use Joomla\Model\AbstractDatabaseModel;
use Joomla\Database\DatabaseDriver;

use JTracker\Database\AbstractDatabaseTable;

/**
 * Abstract base model for the tracker application
 *
 * @since  1.0
 */
abstract class AbstractTrackerDatabaseModel extends AbstractDatabaseModel
{
	/**
	 * The model (base) name
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $name = null;

	/**
	 * The URL option for the component.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $option = null;

	/**
	 * Table instance
	 *
	 * @var    AbstractDatabaseTable
	 * @since  1.0
	 */
	protected $table;

	/**
	 * Project object
	 *
	 * @var    TrackerProject
	 * @since  1.0
	 */
	protected $project;

	/**
	 * Instantiate the model.
	 *
	 * @param   DatabaseDriver  $database  The database adapter.
	 *
	 * @since   1.0
	 */
	public function __construct(DatabaseDriver $database)
	{
		parent::__construct($database);

		// Guess the option from the class name (Option)Model(View).
		if (empty($this->option))
		{
			// Get the fully qualified class name for the current object
			$fqcn = (get_class($this));

			// Strip the base component namespace off
			$className = str_replace('App\\', '', $fqcn);

			// Explode the remaining name into an array
			$classArray = explode('\\', $className);

			// Set the component as the first object in this array
			$this->component = $classArray[0];
		}

		// Set the view name
		if (empty($this->name))
		{
			$this->getName();
		}
	}

	/**
	 * Method to get the model name
	 *
	 * The model name. By default parsed using the class name or it can be set
	 * by passing a $config['name'] in the class constructor
	 *
	 * @return  string  The name of the model
	 *
	 * @since   1.0
	 */
	public function getName()
	{
		if (empty($this->name))
		{
			// Get the fully qualified class name for the current object
			$fqcn = (get_class($this));

			// Explode the name into an array
			$classArray = explode('\\', $fqcn);

			// Get the last element from the array
			$class = array_pop($classArray);

			// Remove Model from the name and store it
			$this->name = str_replace('Model', '', $class);
		}

		return $this->name;
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $name    The table name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 *
	 * @return  AbstractDatabaseTable  A Table object
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function getTable($name = '', $prefix = 'Table')
	{
		if (empty($name))
		{
			$name = $this->getName();
		}

		$class = $prefix . ucfirst($name);

		if (!class_exists($class) && !($class instanceof AbstractDatabaseTable))
		{
			throw new \RuntimeException(sprintf('Table class %s not found or is not an instance of AbstractDatabaseTable', $class));
		}

		$this->table = new $class($this->getDb());

		return $this->table;
	}

	/**
	 * Get the project.
	 *
	 * @return  TrackerProject
	 *
	 * @since   1.0
	 * @throws  \UnexpectedValueException
	 */
	public function getProject()
	{
		if (is_null($this->project))
		{
			throw new \UnexpectedValueException('Project not set');
		}

		return $this->project;
	}

	/**
	 * Set the project.
	 *
	 * @param   TrackerProject  $project  The project.
	 *
	 * @return  $this  Method allows chaining
	 *
	 * @since   1.0
	 */
	public function setProject(TrackerProject $project)
	{
		$this->project = $project;

		return $this;
	}
}
