<!DOCTYPE html>
<html lang="nb" xml:lang="nb">
<head>
<meta charset="utf-8">
<title>{$title|default:"Ras blog"}</title>

{css_tag file="ras.css"}
<link href='http://fonts.googleapis.com/css?family=Over+the+Rainbow' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Cabin+Sketch:bold' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Bangers' rel='stylesheet' type='text/css'>

{script_tag file="prototype.js"}
{script_tag file="scriptaculous.js"}
{script_tag file="tiny_mce/tiny_mce.js"}
{script_tag file="ras.js"}

</head>
<body>	
	<div id="background"></div>
	<header id="site">
		<div id="logo">
			<a href="{$root}"><img src="{$root}images/logo.png" width="294" height="106" alt="RAS logo.png (24,878 bytes)"></a>
		</div>
		{menu}
			{mi href="search"}S&oslash;k{/mi}
			{$now = $smarty.now|date_format:"%Y"}
			{si href=$now name=$now}
				{submenu}
				  {foreach $years as $year}
						{if $year != $now}{mi href="$year"}{$year}{/mi}{/if}
					{/foreach}
				{/submenu}
			{/si}
				{if isset($smarty.session.auth)}
					{if $smarty.session.id == '1'}<!-- TODO: first user in db -->
						{mi href="post/new"}Nytt innlegg{/mi}
						{mi href="upload"}Upload{/mi}
					{/if}
					{mi  href="changeInfo"}Endre Passord{/mi}
					{mi  href="destroy"}Logg ut{/mi}
				{/if}
				{if !isset($smarty.session.auth)}
					{mi  href="login"}Login{/mi}
					{mi  href="register" title="Registrer ny konto"}Registrering{/mi}
				{/if}
				{mi  href="" title="G&aring; tilbake til forsiden"}Forsiden{/mi}
		{/menu}
	</header>
	
	<div id="content">
		{block name="errors"}{if isset($error) and count($error) != 0}
		<div id="errors">
			<h1>{$error}</h1>
		</div>{/if}{/block}
		{block name="content"}{/block}
	</div>
	<footer>
		&copy; RAS 2011 at {$root}
	</footer>

</body>
</html>
