<?php
  namespace Migrations;
  use Core\Migration;

  class Migration1553040803 extends Migration {
    public function up() {
      $table = "products";
      $this->addColumn($table,'brand_id','int',['after'=>'name']);
      $this->AddIndex($table,'brand_id');
    }
  }
