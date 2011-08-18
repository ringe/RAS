{extends file="layout.tpl"}
{block name="content"}
    <div id='loginform' class="activation">
        {form method="POST" action="activate/" id="activationform"}
            <fieldset>
              <legend>Aktivering av konto</legend>
              <p>Skriv inn den aktiveringsn&oslash;kkelen du har f&aring;tt i epost</p>
              <p><input name="activationkey" type="text" placeholder="aDa43fKeOal53a" size=14 maxlength=14 onchange="updateKeyURL(this);"/></p>
              <input type="submit" value="Aktiver konto" />
            </fieldset>
        </form>
        <script></script>
    </div>
{/block}
