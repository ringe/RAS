{extends file="layout.tpl"}

{block name="content"}
<article id="post">
  {if isset($smarty.session.auth)}
  {attachments post_id=$post->id}
	{/if}
	<header>
  	<h1>{$post->title}</h1>
  	<h2>{strftime("%d.%m.%Y", strtotime($post->posted_at))} av  {$post->user()->name}</h2>
  </header>

  <p>{$post->body}</p>
  <p>{$post->counter+1}stk har sett p&aring denne posten!!!</p>
	{include file="comment.tpl"}
	<section id="comments">
	  <ul>
	   {foreach $post->comments() as $comment}
	     <li>
	         <div id="comment-{$comment->id}">
	             <h3 onclick="new Effect.toggle('saying_{$comment->id}', 'blind', { duration: 0.2 }); return false;">
	             	<span>{$comment->user()->name}</span> sier: {$comment->title}</h3>
	             <div id="saying_{$comment->id}" style="display: none;">{$comment->comment}</div>
	             {if $smarty.session.auth=='true' && $smarty.session.id=='1'}
							 	{a href="deleteComment/{$comment->id}"}Slett denne commenten</a>{/if}
	         </div>
	     </li>
	  {/foreach}
			
	  </ul>
	</section>
</article>
{/block}
