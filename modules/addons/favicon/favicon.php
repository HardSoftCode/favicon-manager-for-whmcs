<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined("WHMCS")) die("This file cannot be accessed directly");

function favicon_config()
{
  $configarray = array(
  "name" => "Favicon Manager",
  "description" => "Favicon is a small icon image or logo displayed in the address bar of a web browser. By using this module, you can easily upload and change favicon icon on your WHMCS.",
  "version" => "1.3.3",
  "author" => "<a href=\"http://www.hardsoftcode.com\" target=\"_blank\">HSC</a>",
  "language" => "english",
  "fields" => array(
  "delete" => array ("FriendlyName" => "Delete Module DB", "Type" => "yesno", "Size" => "25", "Description" => "Tick this box to delete the module database on deactivating"),
  ));
  return $configarray;
}

function favicon_activate()
{
  try
  {
    if(!Capsule::schema()->hasTable('mod_favicon'))
    {
      Capsule::schema()->create('mod_favicon', function ($table)
      {
        $table->increments('id');
        $table->text('title');
        $table->text('url');
        $table->text('img');
        $table->text('status');
        $table->tinyInteger('orders');
      });

       Capsule::table('mod_favicon')->insert(array(
               array(
                      'id' => '1',
                      'title' => 'Home',
                      'url' => '',
                      'img' => '740320c85ab2a08c2b54662399ba24e2.png',
                      'status' => '0',
                      'orders' => '1',
                     ),));
    }
  }
  catch (\Exception $e)
  {
    return array('status'=>'error','description'=>'Unable to create table mod_favicon: ' .$e->getMessage());
  }

  return array('status'=>'success','description'=>'Module activated successfully. Click configuration to configure the module');
}

function favicon_deactivate()
{
  $delete = Capsule::table('tbladdonmodules')->where('module', 'favicon')->where('setting', 'delete')->first();

  if($delete->value)
  {
    try
    {
      Capsule::schema()->dropIfExists('mod_favicon');
    }
    catch (\Exception $e)
    {
      return array('status'=>'error','description'=>'Unable to drop tables: ' .$e->getMessage());
    }
  }

  return array('status'=>'success','description'=>'Module deactivated successfully');
}

function favicon_output($vars)
{
  global $CONFIG;

  $modulelink = $vars['modulelink'];
  $LANG       = $vars['_lang'];

  require(dirname( __FILE__ ).'/includes/pages/menu.php');

  if(file_exists(dirname( __FILE__ ).'/includes/pages/'.$_REQUEST['a'].'.php'))
  {
    require(dirname( __FILE__ ).'/includes/pages/'.$_REQUEST['a'].'.php');
  }
  else
  {
    require(dirname( __FILE__ ).'/includes/pages/home.php');
  }
}
