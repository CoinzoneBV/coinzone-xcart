<table cellspacing="1" cellpadding="5" class="settings-table">
    <tr>
        <td class="setting-name">
            <label for="settings_clientCode">{t(#Client Code#)}</label>
        </td>
        <td>
            <input type="text" id="settings_clientCode" name="settings[clientCode]" value="{paymentMethod.getSetting(#clientCode#)}" class="validate[required,maxSize[255]]" />
        </td>
    </tr>
    <tr>
        <td class="setting-name">
            <label for="settings_apiKey">{t(#API Key#)}</label>
        </td>
        <td>
            <input type="text" id="settings_apiKey" name="settings[apiKey]" size="64" value="{paymentMethod.getSetting(#apiKey#)}" class="validate[required,maxSize[255]]" />
        </td>
    </tr>
</table>
