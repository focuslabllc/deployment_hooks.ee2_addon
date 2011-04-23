<?php
// No, I'm not moving this entire docs page into language lines.
// Yes, I am using line break tags for spacing.
// There's no need to write custom CSS for this add-on since it's aimed at devs
?>

<div style="float:left;width:48%;margin-right:4%;">
	
	<h2>Purpose</h2>
	<br/>
	<p>Deployment Hooks is a very basic add-on that simply adds a few hooks to EE which are triggered by ACT URL requests. These requests are intended to be used with automated deployment applications like <a href="http://www.kernel.org/pub/software/scm/git/docs/githooks.html">Git hooks</a>, <a href="http://beanstalkapp.com/">Beanstalk</a> web hooks, <a href="http://www.deployhq.com/">DeployHQ</a> hooks etc.</p>

	<p>The gist is this: <strong>Deployment Hooks doesn't do a darn thing out of the box.</strong></p>

	<h2>Usage</h2>
	<br/>
	<p>In order to use Deployment Hooks you must create your own extension that utilizes the available hooks in the module. There are two hooks (presently). One for pre-deployment actions and another for post-deployment actions. If you don't know how to write your own ExpressionEngine Extensions then <strong>this add-on is not for you</strong>.</p>

	<p>Deployment Hooks maintains a log of each time each hook is used. As a developer you are able to add to this log with each time your method is called. Return an array of strings to be logged during each hook execution.</p>

	<p>Here is how the hooks appear in the code:</p>
	<script src="https://gist.github.com/937347.js"></script>
	<noscript>
	<code><pre>
	if ($this-&gt;_EE-&gt;extensions-&gt;active_hook(&#x27;deployment_hooks_&#x27;.$which.&#x27;_deploy&#x27;) === TRUE)
	{
	    $ext_response = $this->_EE->extensions->call('deployment_hooks_'.$which.'_deploy');
	    $this->response = is_array($ext_response) ? array_merge($this->response,$ext_response) : $this->response ;
	}
	</pre></code>
	</noscript>

	<p>Both hooks use this code so the only difference is the <code>$which</code> variable triggering the correct code.</p>
	
	<h2>Contributing &amp; Support</h2>
	<br/>
	<p>This tool will only be as good and useful as its contributors. We'd love to have feedback and contributions from fellow developers.</p>

	<p>Fork away, send pull requests and file issues in the GitHub repo. Let us know if you have any questions.</p>

	<p>- Focus Lab, LLC Dev team, <a href="mailto:dev@focuslabllc.com">dev@focuslabllc.com</a></p>

</div>

<div style="float:right;width:48%;">
	
	<h2>Setup</h2>
	<br/>
	<p>Once you've got an extension in place you need to point your service's web hooks (or your own scripts) to the URLs listed on the Deployment Hooks homepage. These URLs will be the way your extensions get triggered.</p>

	<p>As an example, you can review this page on Beanstalk's knowledge base: <a href="http://help.beanstalkapp.com/kb/deployments/web-hooks-for-deployments">http://help.beanstalkapp.com/kb/deployments/web-hooks-for-deployments</a></p>
	
	<h2>Sample Extension</h2>
	<br/>
	<p>This download should have come with a simple example extension of toggling the system with Beanstalk's post and pre deployment hooks. To get an idea of how to use this module you can review that file. In a nutshell it does the following:</p>

	<ul style="margin-left:30px">
		<li>Uses the <code>sessions_end</code> hook to effectively turn the system off and on via a 3rd party add-on</li>
		<li>Uses the <code>deployment_hooks_pre_deploy</code> hook to toggle the system off</li>
		<li>Uses the <code>deployment_hooks_post_deploy</code> hook to toggle the system off</li>
	</ul>
	<br/>
	<p>The actual order of operations looks like this:</p>

	<ol style="margin-left:30px">
		<li>Beanstalkapp.com's pre-deployment web hook hits "Deployment Hooks" ACT URL</li>
		<li>Deployment Hooks runs security checks on URL and IP requirements</li>
		<li>Extension "Beanstalk Toggle System" switches on the <code>sessions_end</code> hook effectively turning the system "off"</li>
		<li>Extension returns a log response to Deployment Hooks as an array</li>
		<li>Deployment Hooks logs completed hook execution</li>
		<li>Repeat the above 5 steps but with the post-deployment web hook and respective URL &amp; extensions</li>
	</ol>
	
	<br/>
	
	<h2>Suggestions &amp; Ideas</h2>
	<br/>
	<p>We built Deployment Hooks to be service-agnostic. The goal is for you to be able to use this module as a tool to further enhance your deployment process no matter what approach you're using, be it Beanstalk, DeployHQ custom shell scripts etc.</p>
	
	<p>The primary concept is that each site or project could have a single extension that utilizes Deployment Hooks and executes all necessary actions for pre and post deployments. Don't let that stop you from creating extensions that you can distribute though. It's certainly still possible to use multiple extensions with this module. It's built to support that.</p>
	
</div>

<br style="clear:both"/>