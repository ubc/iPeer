<h2>LTI 1.3 tool debug <u>index</u> page.</h2>

<p>Initial JSON to register the tool to the platform:</p>
<pre style="font-family:monospace;"><?php echo $json; ?></pre>

<h2>LTI-RI</h2>

<h3>Test LTI 1.3 launch with LTI-RI</h3>
<ul>
    <li><a target="_blank" href="https://lti-ri.imsglobal.org/platforms/652/resource_links/12301/connects?user_id=93176">Resource Link flow MECH 328</a></li>
    <li><a target="_blank" href="https://lti-ri.imsglobal.org/platforms/652/resource_links/12302/connects?user_id=93176">Resource Link flow APSC 201</a></li>
    <li><a target="_blank" href="https://lti-ri.imsglobal.org/platforms/652/contexts/5696/deep_links">Deep Link flow</a></li>
</ul>

<h2>Canvas</h2>

<h3>1. Check original roster</h3>
<ul>
    <li><a target="_blank" href="/users/goToClassList/1">MECH 328 enrolment</a></li>
    <li><a target="_blank" href="/users/goToClassList/2">APSC 201 enrolment</a></li>
</ul>

<h3>2. Test LTI 1.3 roster update with Canvas</h3>
<ul>
    <li><a target="_blank" href="http://canvas.docker/courses/1/external_tools/1">Canvas test for MECH 328</a></li>
    <li><a target="_blank" href="http://canvas.docker/courses/2/external_tools/2">Canvas test for APSC 201</a></li>
    <li><a target="_blank" href="http://canvas.docker/courses/3/external_tools/3">Canvas test for ABCD 101</a></li>
</ul>

<h3>3. Recheck updated roster</h3>
<ul>
    <li><a target="_blank" href="/users/goToClassList/1">MECH 328 enrolment</a></li>
    <li><a target="_blank" href="/users/goToClassList/2">APSC 201 enrolment</a></li>
</ul>