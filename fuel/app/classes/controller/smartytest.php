<?php

/**
 * @package  app
 * @extends  Controller
 */
class Controller_Smartytest extends Controller {

	/**
	 * The index action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_index()
	{
		$this->response->body =
			View_Smarty::factory(	'smartytest/index',
									array(	'fuel_version'		=> Fuel::VERSION,
											'smarty_version' 	=> Smarty::SMARTY_VERSION));
	}
}

/* End of file test.php */
