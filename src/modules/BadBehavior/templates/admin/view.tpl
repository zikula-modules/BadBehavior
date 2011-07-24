{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text="BadBehavior access log"}</h3>
</div>

<table class="z-datatable">
    <thead>
        <tr>
            <th>{gt text='Date'}</th>
            <th>{gt text='IP address'}</th>
            <th>{gt text='Assigned Key'}</th>
            <th>{gt text='Message'}</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$items item='i'}
        <tr class="{cycle values="z-odd,z-even"}">
            <td>{$i.date->format('Y-m-d H:i:s')|safetext}</td>
            <td>{$i.ip|safetext}</td>
            <td>{$i.key|safetext}</td>
            <td>{$i.message.response|safetext}/{$i.message.explanation|safetext}/{$i.message.log|safetext}</td>
        </tr>
        {foreachelse}
        <tr class='z-datatableempty'><td colspan='4'>{gt text='There are no log entries'}</td></tr>
        {/foreach}
    </tbody>
</table>
{adminfooter}