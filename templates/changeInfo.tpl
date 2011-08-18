{extends file="layout.tpl"}
{block name="content"}
    <div id='userform'>
        <form method="POST">
	        <fieldset class="float">
	            <legend>Endre passord</legend> 
                
	                <label>Gammelt passord</label> 
	                
	                {literal}
	                <input name="oldpassword" type="password" maxlength="100" size="25" required pattern='(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*'  />
	                {/literal}
	                
	              	<label>Nytt passord</label> 
	                
	                {literal}
	                <input name="newpassword" type="password" maxlength="100" size="25" required pattern='(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*'  />
	                {/literal}

	                <input type="submit" name="submitok" value="Endre" /> 
	               
	        </fieldset>
        </form> 
    </div>
{/block}
