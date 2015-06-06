<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class ImportCategoryCommand extends Command implements ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;
	public $xlsRows;
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	

	public function __construct( $xlsRows ) {
		// code...
		$this->xlsRows = $xlsRows;
	}

}
