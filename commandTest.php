<?php

class Core_BatchCase extends Drush_TestCase {
  public function testInvoke() {
    $expected = array(
      'drush_unit_invoke_init',
      'drush_unit_invoke_validate',
      'drush_unit_pre_unit_invoke',
      'drush_unit_invoke',
      'drush_unit_post_unit_invoke',
      'drush_unit_post_unit_invoke_rollback',
      'drush_unit_pre_unit_invoke_rollback',
      'drush_unit_invoke_validate_rollback',
    );
    
    // We expect a return code of 1 so just call execute() directly.
    $this->execute(UNISH_DRUSH . ' unit-invoke --include=' . escapeshellarg(dirname(__FILE__)), 1);
    $called = json_decode($this->getOutput());
    $this->assertSame($expected, $called);
  }
  
  
  /*
   * Assert that $command has interesting properties. Reference command by
   * it's alias (dl) to assure that those aliases are built as expected.
   */ 
  public function testGetCommands() {
    $eval = '$commands = drush_get_commands();';
    $eval .= 'print json_encode($commands[\'dl\'])';
    $this->drush('php-eval', array($eval));
    $command = json_decode($this->getOutput());
    
    $this->assertEquals('dl', current($command->aliases));
    $this->assertEquals('download', current($command->{'deprecated-aliases'}));
    $this->assertObjectHasAttribute('version_control', $command->engines);
    $this->assertObjectHasAttribute('package_handler', $command->engines);
    $this->assertEquals('pm-download', $command->command);
    $this->assertEquals('pm', $command->commandfile);
    $this->assertEquals('drush_command', $command->callback);
    $this->assertObjectHasAttribute('examples', $command->sections);
    $this->assertTrue($command->is_alias);
  }
}