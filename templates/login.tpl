{extends file="layout.tpl"}
{block name="content"}
    <div id='userform'>
        <form method="POST">
        <fieldset class="float">
            <legend>Login</legend> 

                <label>Mail adresse</label> 

                <input name="username" type="email" maxlength="100" size="25" value="{$request['email']}"  autofocus/> 
                
                <label>Passord</label> 
                
                <input name="password" type="password" maxlength="100" size="25"/>
                <div class="stopFloat">
                {a href="newpassword"}Glemt passord</a>
                <input type="submit" value="Login" />  </div>        
               
        </fieldset>
        </form> 
    </div>
{/block}
