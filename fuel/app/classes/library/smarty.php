<?php

/**
 * @package  app
 * @extends  View
 */
class Library_Smarty extends View {

	const FILE_EXTENSION		= 'tpl';
	protected static $smarty	= NULL;

	public function __construct($file = null, array $data = null, $encode = null)
	{
		$this->extension = static::FILE_EXTENSION;

		parent::__construct($file, $data, $encode);

		if ( ! static::$smarty)
		{
			static::$smarty = new Smarty();
			static::$smarty->template_dir	= APPPATH . 'tmp/smarty/templates';
			static::$smarty->compile_dir	= APPPATH . 'tmp/smarty/templates_c';
			static::$smarty->config_dir		= APPPATH . 'tmp/smarty/configs';
			static::$smarty->cache_dir		= APPPATH . 'cache/smarty';

			static::$smarty->caching		= Config::get('caching');
			static::$smarty->cache_lifetime	= Config::get('cache_lifetime');

			switch (Config::get('environment'))
			{
				case Fuel::DEVELOPMENT:
				{
					static::$smarty->force_compile	= true;
				} break;

				case Fuel::TEST:
				{
					static::$smarty->force_compile	= true;
					static::$smarty->debugging		= true;
				} break;
			}
		}
	}

	/**
	 * Sets the view filename.
	 *
	 *     $view->set_filename($file);
	 *
	 * @override
	 * @param   string  view filename
	 * @return  View
	 * @throws  Fuel_Exception
	 */
	public function set_filename($file)
	{
		parent::set_filename($file);
	}

	/**
	 * Captures the output that is generated when a view is included.
	 * The view data will be extracted to make local variables. This method
	 * is static to prevent object scope resolution.
	 *
	 *     $output = View::capture($file, $data);
	 *
	 * @override
	 * @param   string  filename
	 * @param   array   variables
	 * @return  string
	 */
	protected static function capture($view_filename, array $view_data)
	{
		// assign the global values for the Smarty template
		foreach (static::$_global_data as $assigned_key => $assigned_value)
		{
			static::$smarty->assign($assigned_key, $assigned_value);
		}

		// assign the local values for the Smarty template
		foreach ($view_data as $assigned_key => $assigned_value)
		{
			static::$smarty->assign($assigned_key, $assigned_value);
		}

		// Capture the view output
		ob_start();

		try
		{
			// Load the view within the current scope
			static::$smarty->display($view_filename);
		}
		catch (\Exception $e)
		{
			// Delete the output buffer
			ob_end_clean();

			// Re-throw the exception
			throw $e;
		}

		// Get the captured output and close the buffer
		return ob_get_clean();
	}
}

class_alias('Library_Smarty', 'View_Smarty');

/* End of file smarty.php */
