<?php
use google\appengine\api\users\UserService;
/**
 * Utility for working with memcache.
 */
class MemcacheUtil
{
  /**
   * Fetches content from memcache if it exists, otherwise calls the $contentFunc
   * to generate content to store in memcache and return.
   */
  public static function serveFromMemcache($key, $contentFunc,
        $expire = 1800) {
    $content = false;
    $isAdmin = false;
    $user = UserService::getCurrentUser();
    if (isset($user) && UserService::isCurrentUserAdmin()) {
      $isAdmin = true;
    }
    if (!$isAdmin) {
      $memcache = new \Memcache;
      $content = $memcache->get($key);
    }
    if ($content === false) {
      if (is_callable($contentFunc)) {
        $content = $contentFunc();
      }
      else {
        throw new \Exception('Content function not callable.');
      }
      if (!$isAdmin) {
        $memcache->set($key, $content, 0, $expire);
      }
    }
    return $content;
  }
  /**
   * Removes an entry from the memcache.
   */
  public static function remove($key) {
    if (true) {
      $memcache = new \Memcache;
      $memcache->delete($key);
    }
  }
}
?>