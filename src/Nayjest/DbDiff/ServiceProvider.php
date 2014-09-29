<?php namespace Nayjest\DbDiff;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Nayjest\DbDiff\Console\DbDiffMakeCommand;
use Route;

class ServiceProvider extends BaseServiceProvider
{

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('nayjest/db-diff');
        Route::controller('admin/diff', 'Nayjest\DbDiff\Controller');
	}

	/**
	 * Register the ervice provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->bind('db-diff::command.make', function($app) {
            return new DbDiffMakeCommand;
        });
        $this->commands([
            'db-diff::command.make'
        ]);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
