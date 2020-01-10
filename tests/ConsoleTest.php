<?php

namespace JustusTheis\Adyatan\Tests;

use Illuminate\Support\Facades\Artisan;

class ConsoleTest extends TestCase
{
	/** @test */
	public function it_supports_interactive_mode()
	{
		$this->artisan('update --interactive')
			->expectsQuestion('Do you wish to put your application in maintenance mode?', true)
			->expectsQuestion('Do you wish to disable maintenance mode afterwards?', true)
			->expectsQuestion('Do you wish to restart your supervisor?', false)
			->expectsQuestion('Do you wish to clear the application caches?', false)
			->expectsQuestion('Do you wish to rebuild the application caches afterwards?', false)
			->expectsQuestion('Do you wish to pull from git?', false)
			->expectsQuestion('Do you wish to update the application dependencies?', false)
			->expectsQuestion('Do you wish to migrate your tables?', false)
			->expectsOutput('Application updated!')
			->expectsOutput('Thank you for using Adyatan!');
	}

	/** @test */
	public function it_stops_when_password_is_set_and_not_entered_correctly()
	{
		config(['adyatan.password' => 'testPassword']);	
		$this->expectException(\RuntimeException::class);
		$this->artisan('update')
			->expectsQuestion('What is the password? (Your Input will not show. This is normal.)', "wrongPassword")
			->expectsOutput('Password incorrect.');
	}
	
	/** @test */
	public function it_continues_when_password_is_set_and_entered_correctly()
	{
		config(['adyatan.password' => 'testPassword']);	
		$this->artisan('update --interactive')
			->expectsQuestion('What is the password? (Your Input will not show. This is normal.)', "testPassword")
			->expectsQuestion('Do you wish to put your application in maintenance mode?', true)
			->expectsQuestion('Do you wish to disable maintenance mode afterwards?', true)
			->expectsQuestion('Do you wish to restart your supervisor?', false)
			->expectsQuestion('Do you wish to clear the application caches?', false)
			->expectsQuestion('Do you wish to rebuild the application caches afterwards?', false)
			->expectsQuestion('Do you wish to pull from git?', false)
			->expectsQuestion('Do you wish to update the application dependencies?', false)
			->expectsQuestion('Do you wish to migrate your tables?', false)
			->expectsOutput('Application updated!')
			->expectsOutput('Thank you for using Adyatan!');
	}

	/** @test */
	public function it_enables_maintenance_mode()
	{
		$this->artisan('update --interactive')
			->expectsQuestion('Do you wish to put your application in maintenance mode?', true)
			->expectsQuestion('Do you wish to disable maintenance mode afterwards?', false)
			->expectsQuestion('Do you wish to restart your supervisor?', false)
			->expectsQuestion('Do you wish to clear the application caches?', false)
			->expectsQuestion('Do you wish to rebuild the application caches afterwards?', false)
			->expectsQuestion('Do you wish to pull from git?', false)
			->expectsQuestion('Do you wish to update the application dependencies?', false)
			->expectsQuestion('Do you wish to migrate your tables?', false)
			->expectsOutput('Put application in maintenance mode.')
			->expectsOutput('Application is in maintenance mode now.')
			->expectsOutput('Application updated!')
			->expectsOutput('Thank you for using Adyatan!');

		$this->assertEquals(true, $this->app->isDownForMaintenance());
	}

	/** @test */
	public function it_disables_maintenance_mode()
	{
		Artisan::call('down');
		$this->artisan('update --interactive')
			->expectsQuestion('Do you wish to put your application in maintenance mode?', false)
			->expectsQuestion('Do you wish to disable maintenance mode afterwards?', true)
			->expectsQuestion('Do you wish to restart your supervisor?', false)
			->expectsQuestion('Do you wish to clear the application caches?', false)
			->expectsQuestion('Do you wish to rebuild the application caches afterwards?', false)
			->expectsQuestion('Do you wish to pull from git?', false)
			->expectsQuestion('Do you wish to update the application dependencies?', false)
			->expectsQuestion('Do you wish to migrate your tables?', false)
			->expectsOutput('Disabling maintenance mode.')
			->expectsOutput('Application is live now.')
			->expectsOutput('Application updated!')
			->expectsOutput('Thank you for using Adyatan!');

		$this->assertEquals(false, $this->app->isDownForMaintenance());
	}
}
