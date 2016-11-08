<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define("Software", true);
define("Album", true); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include $r_path.'includes/_metadata.php'; ?>
<link href="/css/ProImageSoftware_09.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="Container">
  <? include $r_path.'includes/_navigation.php'; ?>
  <div id="Content2" class="Grey">
    <div id="Text">
      <h1 class="HdrTheSoftware"><span>The Software</span></h1>
      <? include $r_path.'software/_navigation.php'; ?>
      <br clear="all" />
      <div class="BgText">
        <div class="BgTextBottom">
          <div class="Column3"> <br clear="all" />
            <h1 class="HdrAlbumDesigner"><span>Album Designer</span></h1>
            <br clear="all" />
            <img 
            src="/image/album_coming_soon.jpg" alt="Album Designer: Coming Spring of '09" width="777" height="288" />
            <br clear="all" />
          </div>
          <br clear="all" />
          <br clear="all" />
        </div>
      </div>
      <br clear="all" />
    </div>
  </div>
  <? include $r_path.'includes/_footer.php'; ?>
</div>
</body>
</html>
