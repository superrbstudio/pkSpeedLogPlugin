<?php

class pkSpeedLogPluginConfiguration extends sfPluginConfiguration
{
  static $registered = false;
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    // Yes, this can get called twice. This is Fabien's workaround:
    // http://trac.symfony-project.org/ticket/8026
    
    if (!self::$registered)
    {
      $this->dispatcher->connect('a.migrateSchemaAdditions', array($this, 'migrate'));
      self::$registered = true;
    }
  }
  
  public function migrate($event)
  {
    $migrate = $event->getSubject();
    if (!$migrate->columnExists('speed_log', 'memory'))
    {
      $migrate->sql(array('ALTER TABLE speed_log ADD COLUMN memory INTEGER default NULL'));
    }
  }
}