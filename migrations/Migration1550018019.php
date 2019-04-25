<?php
  namespace Migrations;
  use Core\Migration;

  class Migration1550018019 extends Migration {
    public function up() {
      $table = "products";
      $this->createTable($table);
      $this->addTimeStamps($table);
      $this->addColumn($table, 'user_id','int');
      $this->addColumn($table, 'name', 'varchar', ['size'=>155]);
      $this->addColumn($table, 'price', 'decimal', ['precision'=>10,'scale'=>2]);
      $this->addColumn($table, 'list', 'decimal', ['precision'=>10,'scale'=>2]);
      $this->addColumn($table, 'shipping', 'decimal', ['precision'=>10,'scale'=>2]);
      $this->addColumn($table, 'featured', 'tinyint');
      $this->addSoftDelete($table);
      $this->addColumn($table,'body','text',['after'=>'shipping']);
      $this->addIndex($table,'user_id');
      $this->addIndex($table,'featured');

      $table = "product_images";
      $this->createTable($table);
      $this->addColumn($table,'product_id','int');
      $this->addColumn($table,'name','varchar',['size'=>255]);
      $this->addColumn($table,'url','varchar',['size'=>255]);
      $this->addSoftDelete($table);
      $this->addIndex($table,'product_id');
    }
  }
