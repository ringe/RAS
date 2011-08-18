{if $smarty.session.auth=='true'}
    <section id="commentform">
        {form action="comment"}
            <fieldset>
                 <legend id="commentform_title" onclick="commentLegendClick()">Klikk for &aring; skrive kommentar</legend>
                 <div id="form_elements" style="display:none;">
	                 <label><b>Tittel:</b> </label>
	                 <input name="title" type="text" maxlength="40" placeholder="Dette er bra..."/>
	                 <textarea name="comment"></textarea>
	                 
	                 <input type='hidden' name='post_id' value='{$post->id}'/>
	                 <input class='submit' type="submit" name="submitok" value="Lagre innlegg" /> 
                 </div>
            </fieldset> 
        </form>
		</section>
{/if}