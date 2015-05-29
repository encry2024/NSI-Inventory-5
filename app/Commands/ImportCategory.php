<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class ImportCategory extends Command implements ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(Category $category) {
		$this->category = $category;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle() {
		//
	}

}
