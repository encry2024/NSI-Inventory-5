<?php namespace App\Handlers\Commands;

use App\Commands\ImportCategoryCommand;
use Illuminate\Queue\InteractsWithQueue;
use App\Category;

class ImportCategoryCommandHandler {

	
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
	 * @param  ImportCategoryCommand  $command
	 * @return void
	 */
	public function handle(ImportCategoryCommand $command)
	{
		foreach ($command->sheet as $row) {
			$new_information = new Information();
			$new_information->device_id = $row['device_id'];
			$new_information->field_id = $row['field_id'];
			$new_information->value = $row['value'];
			$new_information->save();
		}
		//return count($ctr);
		return redirect()->back();
	}

}
