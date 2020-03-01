<?php

namespace app\kernel;

use app\handler\Handler;
use app\templates\Templates;
use app\database\DatabaseConnection;

class Kernel
{
  /**
   * @var Kernel
   */
  private static $instance;

  /**
   * Initialization of project resources.
   */
  private function __construct()
  {
    DatabaseConnection::getInstance();

    if (defined("AJAX")) {
      new Handler();
      exit;
    }

    Templates::includeTemplates();
  }

  /**
   * @return Kernel
   */
  public static function getInstance()
  {
    if (self::$instance == null) {
      self::$instance = new self();
    }

    return self::$instance;
  }
}