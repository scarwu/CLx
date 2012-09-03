<?php

class clx extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		$help = new clx_help();
		$help->Run();
	}
}
