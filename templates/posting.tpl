{extends file="layout.tpl"}
    {block name="content"}
        <div id="postform"> 
            <form method="POST">
            <fieldset class="float">
                 <legend>Skriv nytt innlegg</legend>
                 
                 <label><b>Tittel:</b> </label>
                 
                 <input name="title" type="text" maxlength="40" placeholder="Skriv tittel her"/>
                 
                 <input name='user_id' type='hidden' value='{$post->user_id}'/>
                 
                 <label><b>Tags:</b> </label>
								 <input name="tags" type="text" maxlength="40" placeholder="her, kan, du ha, tags"/>
            </fieldset>
            <fieldset>
                 <textarea name="summary">Skriv sammendrag her (bytt ut denne teksten)</textarea>
                 <br/>
                 <textarea name="body">Skriv innlegget her (bytt ut denne teksten)</textarea>
                 <br/>
                 
                 <input class='submit' type="submit" name="submitok" value="Lagre innlegg" /> 
            </fieldset>     
            </form>
</div>
{/block}