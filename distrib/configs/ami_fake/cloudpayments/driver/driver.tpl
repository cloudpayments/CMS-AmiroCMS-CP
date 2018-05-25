<!--#set var="settings_form" value="
    <tr>
        <td>%%public_id%%:</td>
        <td><input type="text" name="public_id" class="field" value="##public_id##" size="40"></td>
    </tr>
    <tr>
        <td>%%secret_key%%:</td>
        <td><input type="text" name="secret_key" class="field" value="##secret_key##" size="40"></td>
    </tr>
    <tr>
        <td>%%receipt%%:</td>
        <td><input type="checkbox" name="receipt" class="field" ##if(receipt == '1')## checked ##endif## value="1"></td>
    </tr>
    <tr>
        <td>%%taxation_system%%:</td>
        <td>
            <select name="taxation_system" class="field">
                <option value="0" ##if(taxation_system == '0')## selected ##endif##>%%taxation_system_0%%</option>
                <option value="1" ##if(taxation_system == '1')## selected ##endif##>%%taxation_system_1%%</option>
                <option value="2" ##if(taxation_system == '2')## selected ##endif##>%%taxation_system_2%%</option>
                <option value="3" ##if(taxation_system == '3')## selected ##endif##>%%taxation_system_3%%</option>
                <option value="4" ##if(taxation_system == '4')## selected ##endif##>%%taxation_system_4%%</option>
                <option value="5" ##if(taxation_system == '5')## selected ##endif##>%%taxation_system_5%%</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>%%vat%%:</td>
        <td>
            <select name="vat" class="field" value="##vat##">
                <option value=""    ##if(vat == '')## selected ##endif##>%%vat_none%%</option>
                <option value="0"   ##if(vat == '0')## selected ##endif##>%%vat_0%%</option>
                <option value="10"  ##if(vat == '10')## selected ##endif##>%%vat_10%%</option>
                <option value="18"  ##if(vat == '18')## selected ##endif##>%%vat_18%%</option>
                <option value="110" ##if(vat == '110')## selected ##endif##>%%vat_110%%</option>
                <option value="118" ##if(vat == '118')## selected ##endif##>%%vat_118%%</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>%%vat_delivery%%:</td>
        <td>
            <select name="vat_delivery" class="field" value="##vat_delivery##">
                <option value=""    ##if(vat_delivery == '')## selected ##endif##>%%vat_none%%</option>
                <option value="0"   ##if(vat_delivery == '0')## selected ##endif##>%%vat_0%%</option>
                <option value="10"  ##if(vat_delivery == '10')## selected ##endif##>%%vat_10%%</option>
                <option value="18"  ##if(vat_delivery == '18')## selected ##endif##>%%vat_18%%</option>
                <option value="110" ##if(vat_delivery == '110')## selected ##endif##>%%vat_110%%</option>
                <option value="118" ##if(vat_delivery == '118')## selected ##endif##>%%vat_118%%</option>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2">%%notify_urls%%:</td>
    </tr>
    <tr>
        <td>%%url_check%%:</td>
        <td><input readonly class="field" size="60" value="##root_path_www##?eshop_final.php?cp_action=check"></td>
    </tr>
    <tr>
        <td>%%url_pay%%:</td>
        <td><input readonly class="field" size="60" value="##root_path_www##?eshop_final.php?cp_action=pay"></td>
    </tr>
    <tr>
        <td>%%url_fail%%:</td>
        <td><input readonly class="field" size="60" value="##root_path_www##?eshop_final.php?cp_action=fail"></td>
    </tr>
    <tr>
        <td>%%url_refund%%:</td>
        <td><input readonly class="field" size="60" value="##root_path_www##?eshop_final.php?cp_action=refund"></td>
    </tr>
"-->

<!--#set var="checkout_form" value="
<form name="paymentform##billing_driver##" action="##process_url##" method="post">
    ##hiddens##
</form>
"-->

<!--#set var="pay_form" value="
<form id="paymentform##billing_driver##" name="paymentform" action="#" method="post">
    <input type="submit" class="btn" value="Оплатить" />
</form>
<script src="https://widget.cloudpayments.ru/bundles/cloudpayments"></script>
<script>
    (function(show_widget_callback) {
        var form = document.getElementById('paymentform##billing_driver##');
        if (form.addEventListener) {
            form.addEventListener('submit', show_widget_callback, false);
        } else {
            form.attachEvent('onsubmit', show_widget_callback);
        }
    })(function(e) {
        var evt = e || window.event; // Совместимость с IE8
        if (evt.preventDefault) {
            evt.preventDefault();
        } else {
            evt.returnValue = false;
            evt.cancelBubble = true;
        }
        var widget = new cp.CloudPayments({language: '##widget_language##'});
        widget.charge(##widget_params##, '##return##', '##cancel##');
    });
</script>
"-->
