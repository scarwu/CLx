<?php

class clx_help extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		NanoIO::Writeln("Welcome to use CLx.", 'yellow');
		NanoIO::Writeln("Available Commads:");
		NanoIO::Writeln("    help            - Show help.");
	}
}
