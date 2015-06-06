<?php namespace App\Handlers\Commands;

use App\Commands\ImportInformationCommand;

use Illuminate\Queue\InteractsWithQueue;

use App\Information;

class ImportInformationCommandHandler {

	/**
	 * Create the command handler.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the command.
	 *
	 * @param  ImportInformationCommand  $command
	 * @return void
	 */
	public function handle(ImportInformationCommand $command)
	{
		//
		foreach ($command->xlsRows as $row) {
			$new_category = new Information();
			$new_category->name = $row['name'];
			$new_category->save();
		}
		//return count($ctr);
		return redirect()->back();
	}

}
