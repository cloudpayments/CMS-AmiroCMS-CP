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
        <td>%%payment_scheme%%:</td>
        <td>
            <select name="payment_scheme" class="field">
                <option value="sms" ##if(payment_scheme == 'sms')## selected ##endif##>%%sms%%</option>
                <option value="dms" ##if(payment_scheme == 'dms')## selected ##endif##>%%dms%%</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>%%skin%%:</td>
        <td>
            <select name="skin" class="field">
                <option value="classic" ##if(skin == 'classic')## selected ##endif##>%%classic%%</option>
                <option value="modern" ##if(skin == 'modern')## selected ##endif##>%%modern%%</option>
                <option value="mini" ##if(skin == 'mini')## selected ##endif##>%%mini%%</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>%%cp_lang%%:</td>
        <td>
            <select name="cp_lang" class="field">
                <option value="ru-RU" ##if(cp_lang == 'ru-RU')## selected ##endif##>Русский</option>
                <option value="en-US" ##if(cp_lang == 'en-US')## selected ##endif##>English</option>
                <option value="uk" ##if(cp_lang == 'uk')## selected ##endif##>Український</option>
                <option value="lv" ##if(cp_lang == 'lv')## selected ##endif##>Latviešu</option>
                <option value="az" ##if(cp_lang == 'az')## selected ##endif##>Azərbaycan</option>
                <option value="kk" ##if(cp_lang == 'kk')## selected ##endif##>Русский (часовой пояс ALMT)</option>
                <option value="kk-KZ" ##if(cp_lang == 'kk-KZ')## selected ##endif##>Қазақ</option>
                <option value="pl" ##if(cp_lang == 'pl')## selected ##endif##>Polski</option>
                <option value="pt" ##if(cp_lang == 'pt')## selected ##endif##>Português</option>
                <option value="cs-CZ" ##if(cp_lang == 'cs-CZ')## selected ##endif##>Česky</option>
            </select>
        </td>
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
                <option value="20"  ##if(vat == '20')## selected ##endif##>%%vat_20%%</option>
                <option value="110" ##if(vat == '110')## selected ##endif##>%%vat_110%%</option>
                <option value="120" ##if(vat == '120')## selected ##endif##>%%vat_120%%</option>
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
                <option value="20"  ##if(vat_delivery == '20')## selected ##endif##>%%vat_20%%</option>
                <option value="110" ##if(vat_delivery == '110')## selected ##endif##>%%vat_110%%</option>
                <option value="120" ##if(vat_delivery == '120')## selected ##endif##>%%vat_120%%</option>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2">%%notify_urls%%:</td>
    </tr>
    <tr>
        <td>%%url_check%%:</td>
        <td><input readonly class="field" size="60" value="##root_path_www##eshop_final.php/?cp_action=check"></td>
    </tr>
    <tr>
        <td>%%url_pay%%:</td>
        <td><input readonly class="field" size="60" value="##root_path_www##eshop_final.php/?cp_action=pay"></td>
    </tr>
    <tr>
        <td>%%url_fail%%:</td>
        <td><input readonly class="field" size="60" value="##root_path_www##eshop_final.php/?cp_action=fail"></td>
    </tr>
    <tr>
        <td>%%url_сonfirm%%:</td>
        <td><input readonly class="field" size="60" value="##root_path_www##eshop_final.php/?cp_action=сonfirm"></td>
    </tr>
    <tr>
        <td>%%url_refund%%:</td>
        <td><input readonly class="field" size="60" value="##root_path_www##eshop_final.php/?cp_action=refund"></td>
    </tr>
    <tr>
        <td>%%url_сancel%%:</td>
        <td><input readonly class="field" size="60" value="##root_path_www##eshop_final.php/?cp_action=сancel"></td>
    </tr>
    <input type="hidden" name="calculationPlace" value="##root_path_www##">
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
<script src="https://widget.cloudpayments.ru/bundles/cloudpayments?cms=Amiro"></script>
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
        ##if(payment_scheme == 'sms')## widget.charge(##widget_params##, '##return##', '##cancel##')##endif##;
        ##if(payment_scheme == 'dms')## widget.auth(##widget_params##, '##return##', '##cancel##')##endif##;
    });
</script>
"-->
