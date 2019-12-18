# LTI Names Roles Provisioning Service

[LTI_Names_Roles_Provisioning_Service.php](vendor/imsglobal/lti-1p3-tool/src/lti/LTI_Names_Roles_Provisioning_Service.php)

## Model

[app/models/lti13.php](app/models/lti13.php)

- `Lti13::get_nrps()`
- `Lti13::get_members()`


## View

[app/views/lti13/launch.ctp](app/views/lti13/launch.ctp)

```diff
+ <hr>
+
+ <h3>Names Roles Provisioning Service</h3>
+
+ <p>Members</p>
+ <pre style="font-family:monospace;"><?php echo $members; ?></pre>
```
