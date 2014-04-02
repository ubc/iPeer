<div id='bannerLarge' class='banner'>
<div id='ipeerLogo'>
<a href='<?php echo $this->Html->url('/')?>' id='home'>
<?php
echo $html->image('layout/ipeer_logo.png',
    array('id'=>'bannerLogoImgLeft', 'alt'=>'logo')
);
?>
<span id="ipeerI">i</span><span id="ipeerText">Peer</span> <span id='bannerLogoText'><?php echo IPEER_VERSION?> with TeamMaker</span>
</a>
</div>
<div id='customLogo'>
<?php
// eg. university logo
if (isset($customLogo) && !empty($customLogo) && !empty($customLogo['SysParameter']['parameter_value'])) {
    if (substr($customLogo['SysParameter']['parameter_value'], 0, 4) == 'http') {
        // image from another server
        $url = $customLogo['SysParameter']['parameter_value'];
    } else {
        $url = 'layout/'.$customLogo['SysParameter']['parameter_value'];
    }
    echo $html->image($url, array('id'=>'bannerLogoImgRight', 'alt' => 'custom')
    );
}
?>
</div>
</div>
