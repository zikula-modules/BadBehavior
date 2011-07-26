{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text="Access log"}</h3>
</div>

<table class="z-datatable">
    <thead>
        <tr>
            <th>{gt text='Date'}</th>
            <th>{gt text='IP address'}</th>
            <th>{gt text='Assigned Key'}</th>
            <th>{gt text='Message'}</th>
            <th>{gt text='Actions'}</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$items item='i'}
        <tr class="{cycle values="z-odd,z-even"}">
            <td>{$i.date->format('Y-m-d H:i:s')|safetext}</td>
            <td>{$i.ip|safetext}</td>
            <td>{$i.key|safetext}</td>
            <td>{$i.message.response|safetext}/{$i.message.explanation|safetext}/{$i.message.log|safetext}</td>
            <td><a href='{modurl modname='BadBehavior' type='admin' func='display' id=$i.id}'>{img modname='core' set='icons/extrasmall' src='14_layer_visible.png' __title='View' __alt='View' class='tooltips'}</a></td>
        </tr>
        {foreachelse}
        <tr class='z-datatableempty'><td colspan='5'>{gt text='There are no log entries'}</td></tr>
        {/foreach}
    </tbody>
</table>
{pager rowcount=$totalrows limit=$modvars.BadBehavior.itemsperpage posvar='offset'}
{adminfooter}