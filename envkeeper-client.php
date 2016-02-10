<?php

/**
* EnvKeeper client
*/
class EnvKeeperClient {

  // ---------------------------------------------------------------------
  // Static variables

  private static $settings;

  // ---------------------------------------------------------------------
  // Methods

  /**
   * Private constructor to prevent creating a new instance of the
   * *Singleton* via the `new` operator from outside of this class.
   */
  private function __construct($api_url, $access_token) {}

  /**
   * Setup.
   */
  public static function setup($api_url, $access_token)
  {
    $json_result = file_get_contents($api_url . '/settings.json');
    if (!$json_result) {
      throw new Exception("Can't connect to EnvKeeper API.");
    }
    $settings = json_decode($json_result);
    if (!$settings) {
      throw new Exception("Can't decode EnvKeeper response.");
    }
    $filtred_settings = [];
    foreach ($settings as $setting) {
      if (empty($setting->name) || empty($setting->value)) {
        throw new Exception("Malfomed setting:" . serialize($setting));
      }
      $filtred_settings[$setting->name] = $setting->value;
    }
    if (empty($filtred_settings)) {
      throw new Exception("No settings loaded");
    }
    static::$settings = $filtred_settings;
  }

  public static function get($setting_name)
  {
    return static::$settings[$setting_name];
  }

  // ---------------------------------------------------------------------

}
