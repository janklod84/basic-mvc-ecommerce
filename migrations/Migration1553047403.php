<?php
  namespace Migrations;
  use Core\Migration;

  class Migration1553047403 extends Migration {
    public function up() {
        $this->addColumn('brands','user_id','int');
        $this->addIndex('brands','user_id');
    }
  }
