<h2>LTI 1.3 tool debug <u>LTI_Message_Launch</u> data.</h2>

<?php if (!empty($nrps_members)): ?>
<h3>LTI_Names_Roles_Provisioning_Service members</h3>
<pre style="font-family:monospace;"><?php echo json_encode($nrps_members, 448); ?></pre>
<? endif; ?>

<?php if (!empty($ags_grades)): ?>
<h3>LTI_Assignments_Grades_Service grades</h3>
<pre style="font-family:monospace;"><?php echo json_encode($ags_grades, 448); ?></pre>
<? endif; ?>

<?php if (!empty($dl_response)): ?>
<h3>LTI_Deep_Link response</h3>
<pre style="font-family:monospace;"><?php echo json_encode($dl_response, 448); ?></pre>
<? endif; ?>
