{extends file="layout.tpl"}
{block name="content"}
<body>
	<section id="uploadform">
	{form action="upload" enctype="multipart/form-data"}
		<div>
		<p> Velg en post du vil ha vedlegget/vedleggene til</p>
		<select name="post_id">
		{foreach $posts as $post}
			<option value ="{$post->id}">{$post->title}</option>
		{/foreach} 
	</select>
	</div>

		<input type="file" value="" name="upload[]" id="upload" multiple>
		<input name="MAX_FILE_SIZE" value="8576" type="hidden"/>
		<button type="submit">Upload!</button>
		
	</form>
</section>
</body>
{/block}