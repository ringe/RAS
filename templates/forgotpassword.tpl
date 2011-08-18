{extends file="layout.tpl"}
{block name="content"}
    <div id='userform'>
        <form method="POST">
          <fieldset class="float">
              <legend>Glemt passord</legend> 
                
                  <label>Mailadresse</label> 
                  
                  {literal}
                  <input name="mail" type="email" maxlength="100" size="25" value="Din mailadresse"  autofocus/>   
                  {/literal}
                  
                  <input type="submit" name="submitok" value="F&aring; nytt passord" /> 
                 
          </fieldset>
        </form> 
    </div>
{/block}
