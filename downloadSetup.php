<?php 

if(!isset($_GET["setup"]))
{
    return false;
}

if($_GET["setup"] == "windows")
{
    header("Location: https://downloads.zoolz.com/zoolz2/BigMINDSetup.exe");
}
elseif($_GET["setup"] == "mac")
{
    header("Location: https://downloads.zoolz.com/zoolz2/BigMIND.pkg");
}
elseif($_GET["setup"] == "android")
{
    header("Location: https://play.google.com/store/apps/details?id=com.genie9.intelli");
}
elseif($_GET["setup"] == "ios")
{
    header("Location: https://apps.apple.com/us/app/zoolz-intelligent/id1141710813?ls=1");
}
?>