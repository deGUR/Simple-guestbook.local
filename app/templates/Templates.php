<?php

namespace app\templates;

class Templates
{
  /**
   * Connecting project templates.
   *
   * @return array
   */
  public static function includeTemplates()
  {
    return [
      include "header.php",
      include "main.php",
      include "footer.php",
    ];
  }
}