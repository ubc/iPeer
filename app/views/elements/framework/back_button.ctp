<?php
// The Back button at the top of a template preview page, in the same place
// for each template type. A preview that opened in a new tab has no page to
// go back to, so the button closes the tab.
?>
<div class="back-button">
    <input type="button" name="Back" value="<?php __('Back') ?>" onClick="(history.length > 1) ? history.back() : window.close();">
</div>
