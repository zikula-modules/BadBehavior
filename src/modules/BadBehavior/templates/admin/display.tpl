{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="log" size="small"}
    <h3>{gt text="Log entry"}</h3>
</div>

<table class="z-datatable">
    <tr class="{cycle values="z-odd,z-even"}">
        <th>{gt text="IP address"}</th>
        <td>{$ip|safetext}</td>
    </tr>
    <tr class="{cycle values="z-odd,z-even"}">
        <th>{gt text="Date"}</th>
        <td>{$date->format('Y-m-d H:i:s')|safetext}</td>
    </tr>
    <tr class="{cycle values="z-odd,z-even"}">
        <th>{gt text="Request method"}</th>
        <td>{$request_method|safetext}</td>
    </tr>
    <tr class="{cycle values="z-odd,z-even"}">
        <th>{gt text="Request URI"}</th>
        <td>{$request_uri|safetext}</td>
    </tr>
    <tr class="{cycle values="z-odd,z-even"}">
        <th>{gt text="Server protocol"}</th>
        <td>{$server_protocol|safetext}</td>
    </tr>
    <tr class="{cycle values="z-odd,z-even"}">
        <th>{gt text="Http headers"}</th>
        <td>{$http_headers|safetext}</td>
    </tr>
    <tr class="{cycle values="z-odd,z-even"}">
        <th>{gt text="User agent"}</th>
        <td>{$user_agent|safetext}</td>
    </tr>
    <tr class="{cycle values="z-odd,z-even"}">
        <th>{gt text="Request entity"}</th>
        <td>{$request_entity|safetext}</td>
    </tr>
    <tr class="{cycle values="z-odd,z-even"}">
        <th>{gt text="Key"}</th>
        <td>{$key|safetext}</td>
    </tr>
    <tr class="{cycle values="z-odd,z-even"}">
        <th>{gt text="Response"}</th>
        <td>{$message.response|safetext}</td>
    </tr>
    <tr class="{cycle values="z-odd,z-even"}">
        <th>{gt text="Explanation"}</th>
        <td>{$message.explanation|safetext}</td>
    </tr>
    <tr class="{cycle values="z-odd,z-even"}">
        <th>{gt text="Log"}</th>
        <td>{$message.log|safetext}</td>
    </tr>
</table>
{adminfooter}