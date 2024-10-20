<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if(!defined("WHMCS")) die("This file cannot be accessed directly");

if($_REQUEST['p'] == '')
{
  if(!is_writable(ROOTDIR.'/modules/addons/favicon/img'))
  {
    echo '<div class="alert alert-warning" style="font-size:14px">The directory <strong>'.ROOTDIR.'/modules/addons/favicon/img</strong> need CHMOD 777 permissions so that the module works correctly</div>';
  }

  if($_REQUEST['success'])
  {
    echo ' <script>$(document).ready( function(){ window.setTimeout( function(){ $(".alert").slideUp(); }, 2500);});</script>';
    echo '<div class="alert alert-success" style="font-size:14px"><strong><span class="fas fa-check"></span> '.$LANG['success'].'</strong> '.$LANG['successhelp'].'</div>';
  }

  echo '<h1><span class="fas fa-home"></span> '.$LANG['home'].'</h1>';

  echo '<p><a class="btn btn-primary" href="'.$modulelink.'&p=manage" role="button"><i class="fas fa-plus"></i> '.$LANG['createnewfavicon'].'</a></p>';
  echo '<div class="panel panel-default">
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th>'.$LANG['image'].'</th>
                <th>'.$LANG['title'].'</th>
                <th>'.$LANG['pageurl'].'</th>
                <th>'.$LANG['sortorder'].'</th>
                <th>'.$LANG['status'].'</th>
                <th></th>
              </tr>
            </thead>
            <tbody>';
  $result = Capsule::table('mod_favicon')->orderBy('orders', 'ASC')->get();
  foreach ($result as $data)
  {
    $id     = $data->id;
    $title  = $data->title;
    $url    = $data->url;
    $img    = $data->img;
    $status = $data->status;
    $orders = $data->orders;

    $statusuot = ($status) ? '<span class="label closed">'.$LANG['disable'].'</span>' : '<span class="label active">'.$LANG['enable'].'</span>';

    $statusbuttons = ($status) ? '<a href="'.$modulelink.'&p=enable&id='.$id.'" class="btn btn-default btn-sm"><i class="fas fa-check"></i> '.$LANG['enable'].'</a>' : '<a href="'.$modulelink.'&p=disable&id='.$id.'" class="btn btn-warning btn-sm"><i class="fas fa-ban"></i> '.$LANG['disable'].'</a>';

    $urllink = ($url) ? '<a href="'.$url.'" target="_blank">'.$url.'</a>' : $LANG['allpages'];

       echo '<tr>
                <td> <img src="../modules/addons/favicon/img/'.$img.'" height="30" width="30"></td>
                <td style="padding-top: 13px;">'.$title.'</td>
                <td style="padding-top: 13px;">'.$urllink.'</td>
                <td style="padding-top: 13px;">'.$orders.'</td>
                <td style="padding-top: 13px;">'.$statusuot.'</td>
                <td>
                  '.$statusbuttons.'
                  <a href="'.$modulelink.'&p=manage&id='.$id.'" class="btn btn-success btn-sm"><i class="fas fa-edit"></i> '.$LANG['edit'].'</a>
                  <a href="'.$modulelink.'&p=delete&id='.$id.'" class="btn btn-danger btn-sm" onclick="return confirm(\''.$LANG['deletehelp'].'\');"><i class="fas fa-trash"></i> '.$LANG['delete'].'</a>
                </td>
              </tr>';
  }

  if(!$id)
  {
    echo '<tr>
            <td colspan="5" class="text-center">'.$LANG['norecordsfound'].'</td>
          </tr>';
  }

      echo '</tbody>
          </table>
        </div>';
}

if($_REQUEST['p'] == 'manage')
{
  $id    = $_REQUEST['id'];
  $sorts = 0;

  if($id)
  {
    $data = Capsule::table('mod_favicon')->where('id', $id)->first();

    $title  = $data->title;
    $url    = $data->url;
    $oldimg = $data->img;
    $status = $data->status;
    $sorts  = $data->orders;
  }

  if($_REQUEST['save'])
  {
    $id      = $_REQUEST['id'];
    $title   = $_REQUEST['title'];
    $url     = $_REQUEST['url'];
    $oldimg  = $_REQUEST['oldimg'];
    $sorts   = $_REQUEST['sorts'];
    $status  = $_REQUEST['status'];
    $imgname = md5(time());
    $imgsize = getimagesize($_FILES["img"]['tmp_name']);

    if(!$title)
    {
      $errormessage = $LANG['error004'];
    }
    elseif($_FILES["img"]['tmp_name'])
    {
      if($_FILES["img"]["type"] == "image/png")
      {
        if($imgsize[0] == '48' && $imgsize[1] == '48')
        {
          if($_FILES['img']['name'])
          {
            if(!$_FILES['img']['error'])
            {
              move_uploaded_file($_FILES["img"]["tmp_name"],ROOTDIR."/modules/addons/favicon/img/" . $imgname.'.png');
              $img = $imgname.'.png';
            }
          }
        }
        else
        {
          $errormessage = $LANG['error001'];
        }
      }
      else
      {
        $errormessage = $LANG['error003'];
      }
    }

    if($img)
    {
      unlink(ROOTDIR.'/modules/addons/favicon/img/'.$oldimg);
    }
    elseif(!$img)
    {
      $img = $oldimg;
    }

    if(!$status)
    {
      $status = 0;
    }

    if(!$errormessage)
    {
      if($id)
      {
        Capsule::table('mod_favicon')->where('id', $id)->update(array(
                                                                  'title'  => $title,
                                                                  'url'    => $url,
                                                                  'img'    => $img,
                                                                  'status' => $status,
                                                                  'orders' => $sorts,
                                                                  ));
      }
      else
      {
        Capsule::table('mod_favicon')->insert(array(
                                                                  'title'  => $title,
                                                                  'url'    => $url,
                                                                  'img'    => $img,
                                                                  'status' => $status,
                                                                  'orders' => $sorts,
                                                                  ));
      }

      header('Location: '.$modulelink.'&success=true');
      exit;
    }
  }

  if($errormessage)
  {
    echo ' <script>$(document).ready( function(){ window.setTimeout( function(){ $(".alert").slideUp(); }, 3000);});</script>';
    echo '<div class="alert alert-danger" style="font-size:14px"><strong><span class="fas fa-exclamation-triangle"></span> '.$LANG['error'].'</strong> '.$errormessage.'</div>';
  }

  if($id){echo '<h1><i class="fas fa-pencil-square-o"></i> '.$LANG['editfavicon'].'</h1>';}else{echo '<h1><i class="fas fa-pencil-square-o"></i> '.$LANG['createnewfavicon'].'</h1>';}

  echo '<style>
          .btn-file {
            position: relative;
            overflow: hidden;
          }
          .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            background: red;
            cursor: inherit;
            display: block;
          }
          input[readonly] {
            background-color: white !important;
            cursor: text !important;
          }
		  </style>';

  echo '<script src="../modules/addons/favicon/includes/html/js/custom.js"></script>';

  echo '<form name="form" action="'.$modulelink.'&p=manage" method="POST" class="form-horizontal" enctype="multipart/form-data">
          <input type="hidden" name="save" value="true">
          <input type="hidden" name="id" value="'.$id.'">
          <div class="panel panel-default">
            <div class="panel-heading">
              <a href="'.$modulelink.'" class="btn btn-danger btn-sm"><i class="fas fa-chevron-left"></i> '.$LANG['back'].'</a>
              <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> '.$LANG['savechanges'].'</button>
              <button type="reset" class="btn btn-default btn-sm"><i class="fas fa-times"></i> '.$LANG['cancel'].'</button>
            </div>
            <div class="panel-body">
              <div class="form-group">
                <label class="col-md-3 control-label">'.$LANG['title'].'</label>
                <div class="col-md-6">
                  <input type="text" name="title" value="'.$title.'" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">'.$LANG['pageurl'].'</label>
                <div class="col-md-6">
                  <input type="text" name="url" value="'.$url.'" class="form-control">
                  <span class="help-block">'.$LANG['pageurlhelp'].'</span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">'.$LANG['fromyourcomputer'].'</label>
                <div class="col-md-6">
                  <div class="input-group">
                      <span class="input-group-btn">
                        <span class="btn btn-primary btn-file">
                          '.$LANG['browse'].' <input type="file" name="img"><input type="hidden" name="oldimg" value="'.$oldimg.'">
                        </span>
                      </span>
                      <input type="text" class="form-control" readonly>
                  </div>
                  <span class="help-block">'.$LANG['fromhelp'].'</span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">'.$LANG['displayorder'].'</label>
                <div class="col-md-3">
                  <input type="text" name="sorts" value="'.$sorts.'" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">'.$LANG['disable'].'</label>
                <div class="col-md-6">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="status" '; if ($status){ echo'CHECKED'; } echo '>'.$LANG['disablehelp'].'
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="panel-footer">
              <a href="'.$modulelink.'" class="btn btn-danger btn-sm"><i class="fas fa-chevron-left"></i> '.$LANG['back'].'</a>
              <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> '.$LANG['savechanges'].'</button>
              <button type="reset" class="btn btn-default btn-sm"><i class="fas fa-times"></i> '.$LANG['cancel'].'</button>
            </div>
          </div>
        </form>';

}

if($_REQUEST['p'] == 'delete')
{
  $id = $_REQUEST['id'];

  $data = Capsule::table('mod_favicon')->where('id', $id)->first();

  $img = $data->img;

  Capsule::table('mod_favicon')->where('id', $id)->delete();

  unlink(ROOTDIR.'/modules/addons/favicon/img/'.$img);

  header('Location: '.$modulelink);
  exit;
}

if($_REQUEST['p'] == 'enable')
{
  $id = $_REQUEST['id'];

  Capsule::table('mod_favicon')->where('id', $id)->update(array('status' => '0'));

  header('Location: '.$modulelink);
  exit;
}

if($_REQUEST['p'] == 'disable')
{
  $id = $_REQUEST['id'];

  Capsule::table('mod_favicon')->where('id', $id)->update(array('status' => 'on'));

  header('Location: '.$modulelink);
  exit;
}
