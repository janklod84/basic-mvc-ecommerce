<?php
  namespace Migrations;
  use Core\Migration;

  class Migration1553041425 extends Migration {
    public function up() {
      $table = "brands";
      $this->createTable($table);
      $this->addTimeStamps($table);
      $this->addColumn($table, 'name', 'varchar', ['size'=>125]);
      $this->addSoftDelete($table);
      $this->addIndex($table,'name');
    }
  }
