<?php namespace Deefour\Presenter\Stubs;

use Illuminate\Support\Collection;

class Article extends Model {

  protected $attributes = [
    'title'          => 'sample article',
    'active'         => true,
    'zip_code'       => '06483',
    'street_address' => '1 Maple Ave',
  ];

  public function isActive() {
    return $this->active;
  }

  public function zipCode() {
    return $this->zip_code;
  }

  public function category() {
    return new Category;
  }

  public function author() {
    return new Author;
  }

  public function relatedEvents() {
    $events = new Collection;

    for ($i = 0; $i < 3; $i++) {
      $events->push(new Event);
    }

    return $events;
  }

}
