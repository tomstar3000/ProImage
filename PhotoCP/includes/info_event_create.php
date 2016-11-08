<?
if (isset($r_path) === false) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
include $r_path . 'security.php';
require_once($r_path . 'scripts/fnct_format_date.php');
require_once($r_path . 'scripts/encrypt.php');
?>
<script type="text/javascript">
    function Open_Sec(ID, ELMNT) {
    if (ELMNT.className == "NavSel") {
    ELMNT.className = '';
    document.getElementById(ID).parentNode.style.display = 'block';
    } else {
    ELMNT.className = 'NavSel';
    document.getElementById(ID).parentNode.style.display = 'none';
    }
    }
</script>
<?
require_once($r_path . 'includes/info_calendar.php');
$HotMenu = "Evnt,Evnt:add";
$Key = array_search($HotMenu, $StrArray);
?>
<h1 id="HdrType2" class="CreateEvnt">
    <div>Create New Event</div>
</h1>
<div id="HdrLinks"><a href="javascript:HotMenu('<? echo $HotMenu; ?>',<? echo ($Key !== false) ? '2' : '1'; ?>);" title="Add to Hot Menu" onmouseover="window.status = 'Add to Hot Menu';
        return true;" onmouseout="window.status = '';
        return true;" class="BtnHotMenu<? echo ($Key !== false) ? 'Added' : ''; ?>" id="BtnHotMenu">Add to Hot Menu</a></div>
<br clear="all" />
<?
require_once($r_path . 'includes/info_event_info.php');
require_once($r_path . 'includes/info_event_presentation.php');
require_once($r_path . 'includes/query_all_event_notifications.php');
require_once($r_path . 'includes/info_event_marketing.php');
require_once($r_path . 'includes/query_preset_discount_codes.php');
require_once($r_path . 'includes/info_event_gift_certificates.php');
?>
<br clear="all" />
<br clear="all" />
<br clear="all" />
<br clear="all" />
<div id="HdrLinks">
    <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status = 'Save Event Information';
        return true;" onmouseout="window.status = '';
        return true;" title="Save Event Information" class="BtnSaveUpload">Save</a>
</div>
