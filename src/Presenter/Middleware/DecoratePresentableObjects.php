<?php namespace Deefour\Presenter\Middleware;

use Closure;
use Deefour\Presenter\Contracts\PresentableContract;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Contracts\View\Factory as ViewFactory;

class DecoratePresentableObjects implements Middleware {

  /**
   * The view factory implementation.
   *
   * @var \Illuminate\Contracts\View\Factory
   */
  protected $view;

  /**
   * Create a new auto-decorator instance.
   *
   * @param  \Illuminate\Contracts\View\Factory  $view
   * @return void
   */
  public function __construct(ViewFactory $view) {
    $this->view = $view;
  }

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next) {
    // Loop through each variable set on the view and attempt to wrap it in
    // its related presenter
    $data = $this->view->getData();

    foreach ($data as $var => $value) {
      if ( ! ($value instanceof PresentableContract)) {
        continue;
      }

      $this->view[$var] = presenter($value);
    }

    return $next($request);
  }

}
