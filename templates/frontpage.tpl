{extends file="layout.tpl"}

{block name="content"}
<section id="posts"> 
	 {foreach $posts as $post}

		   <article id="post_{$post->id}">
			    <h1>{link_to post=$post}</h1>
					{$post->summary}
				  <h3>{p s="kommentar" n=count($post->comments())}</h3>
			 </article>

	 {/foreach}
</section>
{/block}
