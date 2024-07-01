<?php
/**
 * @package JinsPlugin
 */
namespace Inc;

/**
 * Class Init
 * 
 * This class is responsible for registering and instantiating services.
 */
final class Init
{
  /**
   * Get services
   * 
   * This method returns an array of services.
   *
   * @return array An array of services.
   */
  static private function get_services()
  {
    return [
      Pages\Dashboard::class,
      Base\Enqueue::class,
      Base\SettingsLinks::class,
      Base\CustomPostTypeController::class,
      Base\WidgetController::class,
      Base\TestimonialController::class,
    ];
  }

  /**
   * Register services
   * 
   * This method loops through the services returned by the `get_services` method,
   * instantiates each service, and calls the `register` method if it exists.
   */
  static public function register_services()
  {
    foreach (self::get_services() as $class) {
      $service = self::instantiate($class);
      if (method_exists($service, 'register')) {
        $service->register();
      }
    }
  }

  /**
   * Instantiate a service
   * 
   * This method instantiates a service class.
   *
   * @param string $class The class name of the service to instantiate.
   * @return object An instance of the service class.
   */
  static private function instantiate($class)
  {
    return new $class();
  }
}