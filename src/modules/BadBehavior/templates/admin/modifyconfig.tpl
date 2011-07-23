{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="config" size="small"}
    <h3>{gt text="BadBehavior settings"}</h3>
</div>

<form class="z-form" action="{modurl modname="BadBehavior" type="admin" func="updateconfig"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    <fieldset>
        <legend>{gt text='General settings'}</legend>
        <div class="z-formrow">
			<label for="enabled">{gt text='Enable Bad Behavior screening'}</label>
			<input type="checkbox" value="1" id="enabled" name="enabled"{if $modvars.BadBehavior.enabled eq true} checked="checked"{/if} />
        </div>
        <div class="z-formrow">
			<label for="strict">{gt text='Use strict screening'}</label>
			<input type="checkbox" value="1" id="strict" name="strict"{if $modvars.BadBehavior.strict eq true} checked="checked"{/if} />
        </div>
        <div class="z-formrow">
            <label for="logging">{gt text='Enable logging'}</label>
			<input type="checkbox" value="1" id="logging" name="logging"{if $modvars.BadBehavior.logging eq true} checked="checked"{/if} />
        </div>
        <div class="z-formrow">
			<label for="verbose">{gt text='Log everything (not just failed attempts)'}</label>
			<input type="checkbox" value="1" id="verbose" name="verbose"{if $modvars.BadBehavior.verbose eq true} checked="checked"{/if} />
        </div>
        <div class="z-formrow">
			<label for="display_stats">{gt text='Display stats in footer'}</label>
			<input disabled type="checkbox" value="1" id="display_stats" name="display_stats"{if $modvars.BadBehavior.display_stats eq true} checked="checked"{/if} />
        </div>
        <div class="z-formrow">
            <label for="itemsperpage">{gt text='Items per page on log display'}</label>
			<input type="text" value="{$modvars.BadBehavior.itemsperpage}" id="itemsperpage" name="itemsperpage" />
            <em class="z-sub z-formnote">{gt text='"0" for no limit.'}</em>
        </div>
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
        <a href="{modurl modname="BadBehavior" type="admin" func='modifyconfig'}" title="{gt text="Cancel"}">{img modname='core' src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
{adminfooter}