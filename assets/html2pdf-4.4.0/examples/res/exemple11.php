<table style="width:100%;">
    <tr>
        <td id="mon_td_trop_grand" style="width:100%;">
            Test de TD trs grand, en désactivant le test de TD ne devant pas depasser une page<br>
            via la méthode <b>setTestTdInOnePage</b>.<br>
            <table style="width:100%;">
<?php
    for ($i=0; $i<=140; $i++) {
?>
                <tr>
                    <td style="border:1px solid red;width:100%;">
                        test de texte assez long pour engendrer des retours à la ligne automatique...
                        a b c d e f g h i j k l m n o p q r s t u v w x y z
                        a b c d e f g h i j k l m n o p q r s t u v w x y z
                    </td>
                </tr>
<?php
    }
?>
            </table>
        </td>
    </tr>
</table>