<div id="Navigation">
    <ul>
        <li><a href="<?php if (defined('HANDLE')) echo '/' . HANDLE . '/'; ?>index.php<? echo "?" . $_SERVER['QUERY_STRING']; ?>" title="Home"<? if (PAGE == "Home") echo' class="NavSel"'; ?>>Home</a></li>
        <li><a href="<?php if (defined('HANDLE')) echo '/' . HANDLE . '/'; ?>bio.php<? echo "?" . $_SERVER['QUERY_STRING']; ?>" title="Bio"<? if (PAGE == "Bio") echo' class="NavSel"'; ?>>Bio</a></li>
        <li><a href="<?php if (defined('HANDLE')) echo '/' . HANDLE . '/'; ?>public.php?Photographer=<? echo $handle; ?>" title="Public Events"<? if (PAGE == "Public") echo' class="NavSel"'; ?>>Public Events</a></li>
        <li><a href="<?php if (defined('HANDLE')) echo '/' . HANDLE . '/'; ?>contact.php<? echo "?" . $_SERVER['QUERY_STRING']; ?>" title="Contact"<? if (PAGE == "Contact") echo' class="NavSel"'; ?>>Contact</a></li>
    </ul>
    <p>This site requires that you turn off your pop-up blocker and have cookies enabled. </p>
</div>
