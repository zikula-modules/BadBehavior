{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="log" size="small"}
    <h3>{gt text="BadBehavior log entry"}</h3>
</div>

<div class='z-itemlist'>
    <ul>
        <li><span class='itemtitle'>IP address</span><span class='itemdata'>{$ip|safetext}</span></li>
        <li><span class='itemtitle'>Date</span><span class='itemdata'>{$date->format('Y-m-d H:i:s')|safetext}</span></li>
        <li><span class='itemtitle'>Request method</span><span class='itemdata'>{$request_method|safetext}</span></li>
        <li><span class='itemtitle'>Request URI</span><span class='itemdata'>{$request_uri|safetext}</span></li>
        <li><span class='itemtitle'>Server protocol</span><span class='itemdata'>{$server_protocol|safetext}</span></li>
        <li><span class='itemtitle'>Http headers</span><span class='itemdata'>{$http_headers|safetext}</span></li>
        <li><span class='itemtitle'>User agent</span><span class='itemdata'>{$user_agent|safetext}</span></li>
        <li><span class='itemtitle'>Request entity</span><span class='itemdata'>{$request_entity|safetext}</span></li>
        <li><span class='itemtitle'>key</span><span class='itemdata'>{$key|safetext}</span></li>
        <li><span class='itemtitle'>Response</span><span class='itemdata'>{$message.response|safetext}</span></li>
        <li><span class='itemtitle'>Explanation</span><span class='itemdata'>{$message.explanation|safetext}</span></li>
        <li><span class='itemtitle'>Log</span><span class='itemdata'>{$message.log|safetext}</span></li>
    </ul>
</div>
{adminfooter}