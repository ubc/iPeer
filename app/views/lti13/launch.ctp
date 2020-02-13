<h2>LTI 1.3 tool debug <u>launch</u> page.</h2>

<h3>LTI_Message_Launch data</h3>

<p>Launch ID</p>
<pre style="font-family:monospace;"><?php echo $launch_id; ?></pre>

<p>Message type</p>
<p style="padding-top:0;"><strong><?php echo $message_type; ?></strong></p>

<p>$_POST as JSON</p>
<pre style="font-family:monospace;"><?php echo $post_as_json; ?></pre>

<p>JWT header</p>
<pre style="font-family:monospace;"><?php echo $jwt_header; ?></pre>

<p>JWT payload</p>
<pre style="font-family:monospace;"><?php echo $jwt_payload; ?></pre>

<h3>LTI_Names_Roles_Provisioning_Service members</h3>
<pre style="font-family:monospace;"><?php echo $nrps_members; ?></pre>

<?php if (!empty($ags_grades)): ?>
<h3>LTI_Assignments_Grades_Service grades</h3>
<pre style="font-family:monospace;"><?php echo $ags_grades; ?></pre>
<? endif; ?>

<?php if (!empty($dl_response)): ?>
<h3>LTI_Deep_Link response</h3>
<pre style="font-family:monospace;"><?php echo $dl_response; ?></pre>
<? endif; ?>

<h3>Launch log</h3>
<pre style="font-family:monospace;"><?php echo $log_launch; ?></pre>

<h3>Update roster log</h3>
<pre style="font-family:monospace;"><?php echo $log_roster; ?></pre>