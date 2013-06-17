<div id='bannerLarge' class='banner'>
<div id='ipeerLogo'>
<?php
echo $html->image('layout/ipeer_logo.png',
    array('id'=>'bannerLogoImgLeft', 'alt'=>'logo')
);
?>
<span id="ipeerI">i</span><span id="ipeerText">Peer</span> <span id='bannerLogoText'><?php echo IPEER_VERSION?> with TeamMaker</span>
<?php

?>
</div>
<div id='customLogo'>
<?php
// eg. university logo
if (isset($customLogo) && !empty($customLogo) && !empty($customLogo['SysParameter']['parameter_value'])) {
    echo $html->image('layout/'.$customLogo['SysParameter']['parameter_value'],
        array('alt' => 'custom')
    );
}
?>
</div>
</div>
