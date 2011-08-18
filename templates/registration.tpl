{extends file="layout.tpl"}
{block name="content"}
    <div id='userform'>
        <form method="POST">
	        <fieldset class="float">
	            <legend>Ny brukerregistrering</legend> 
	                <label>Fullt navn</label> 
	                {literal}
	                <input name="name" type="text" maxlength="100" size="25" value="{/literal}{$userform['name']}{literal}" required pattern='(?=^.{2,}$)((\s|\w|\d|-)+)' /> 
	                {/literal}
	                <label>Mail adresse</label>
	            
	                <input name="email" type="email" maxlength="100" size="25" value="{$userform['email']}" required /> 
	                
	                <label>Bekreft mail adresse</label>
	            
	                <input name="emailconfirm" type="email" maxlength="100" size="25" value="{$userform['emailconfirm']}" required /> 
	                
	                <label>Passord</label> 
	                
	                {literal}
	                <input name="password" type="password" maxlength="100" size="25" required pattern='(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*'  />
	                {/literal}

	                <input type="submit" value="Registrer" /> 
	               
	        </fieldset>
        </form> 
    </div>
{/block}
