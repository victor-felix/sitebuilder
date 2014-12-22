<?php

namespace meumobi\sitebuilder\presenters\api;

use Mapper;

class VisitorPresenter
{
  public static function present($object)
  {
    return [
      'name' => $object->name(),
      'email' => $object->email(),
    ];
  }

  public static function presentSet($set)
  {
    return array_map(array(__CLASS__, 'present'), $set);
  }
}
