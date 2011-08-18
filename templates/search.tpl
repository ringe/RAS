{extends file="layout.tpl"}
{block name="content"}
    <section id='search'>
        <form method="POST">
        <fieldset>
            <legend>S&oslash;k</legend> 

                <input name="keywords" type="text" size="25" autofocus value='{$keywords}' />
                
                <input type="submit" value="S&oslash;k n&aring;" />          
               
        </fieldset>
        </form> 
    </section>
    <section id="hits">
    {if isset($hits)}
    {if !empty($hits)}
    	<ul>
    	{foreach $hits as $post}
    		<li>
    			<div>
    				<h2>{link_to post=$post}</h2>
    				<p>{$post->summary|strip_tags|truncate:115:"---":true}
    					{link_to post=$post title="Les mer"}
    				</p>
    			</div>
    		</li>
    	{/foreach}
    	</ul>
    	<h1>{count($hits)} treff</h1>
    {else}
    	<h1>Ingen treff</h1>
    {/if}
    {/if}
    </section>
{/block}
