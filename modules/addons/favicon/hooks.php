<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if(!defined("WHMCS")) die("This file cannot be accessed directly");

function Favicon_GetUrl()
{
  $protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';

  return $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}

function Favicon_ClientAreaHeadOutput_Hook($vars)
{
  $systemURL = App::getSystemUrl();

  $data = Capsule::table('mod_favicon')->where('url', Favicon_GetUrl())->where('status', '0')->first();

  if($data->id)
  {
    $hook = "<link rel=\"shortcut icon\" type=\"image/png\" href=\"{$systemURL}modules/addons/favicon/img/".$data->img."\"/>\r";
  }
  else
  {
    $data2 = Capsule::table('mod_favicon')->where('url', '')->where('status', '0')->first();

    $hook = "<link rel=\"shortcut icon\" type=\"image/png\" href=\"{$systemURL}modules/addons/favicon/img/".$data2->img."\"/>\r";
  }

  return $hook;
}

add_hook("ClientAreaHeadOutput",1,"Favicon_ClientAreaHeadOutput_Hook");

add_hook('AdminAreaHeadOutput', 548751, function($vars)
{
  $systemURL = App::getSystemUrl();

  if(Capsule::schema()->hasTable('mod_favicon'))
  {
    if($data = Capsule::table('mod_favicon')->where('url', '')->where('status', '0')->first())
    {
      return <<<HTML
    <link rel="shortcut icon" type="image/png" href="{$systemURL}modules/addons/favicon/img/{$data->img}"/>
HTML;

    }
  }
});
