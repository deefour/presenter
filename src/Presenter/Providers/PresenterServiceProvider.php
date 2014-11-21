<?php namespace Deefour\Authorizer\Providers;

use Deefour\Presenter\Factory;
use Illuminate\Support\ServiceProvider;

class AuthorizationServiceProvider extends ServiceProvider {

  /**
   * Indicates if loading of the provider is deferred.
   *
   * @var bool
   */
  protected $defer = true;



  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register() {
    $this->app->bindShared('presenter', function() {
      return new Factory;
    });
  }

  /**
   * Get the services provided by the provider.
   *
   * @return array
   */
  public function provides() {
    return [ 'presenter' ];
  }

}
