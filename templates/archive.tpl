{extends file="layout.tpl"}

{block name="content"}
<section id="archive">
  <header>
    <h1>Velkommen til arkivet for {$yr}</h1>
    {if isset($month)}<h2>{$month}</h2>{/if}
  </header>
		{foreach $posts as $post}
		<article id="post-{$post->id}">
			{a href="post/id"|replace:'id':$post->id}<b>{$post->title}</b></a> ({$post->comments()|@count})<br/>
			<span>{$post->posted_at}</span>
	 </article>
	 {/foreach}
</section>
<div class="stopFloat"></div>
{/block}
