<style type="text/css">
<!--
table { vertical-align: top; }
tr    { vertical-align: top; }
td    { vertical-align: top; }
}
-->
</style>
<page backcolor="#FEFEFE"  backimgx="center" backimgw="100%" footer="date;heure;page" style="font-size: 12pt">
     <page_header>
        <table style="width: 100%; " >
            <tr>
                <td style="text-align: left;    width: 33%"></td>
                <td style="text-align: center;    width: 34%"></td>
                <td style="text-align: right;    width: 33%"><?php echo date('d/m/Y'); ?></td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table style="width: 100%;">
            <tr>
                <td style="text-align: left;    width: 50%"> </td>
                <td style="text-align: right;    width: 50%">GENERADO POR
EDY FELIX TARQUI GUARACH</td>
            </tr>
        </table>
    </page_footer>
   
    <table cellspacing="0" style="width: 100%; border: solid 1px">
        <thead>
            <tr>
                <td style="width: 10%;color: #444444;">
                <img style="width: 100%;" src="./res/logo.gif" alt="Logo">
            </td>
                            <td colspan="4" style=" text-align: center; font-size: 10pt;" >
                            GOBIERNO AUTONOMO MUNICIPAL DE EL ALTO<br>
                            SECRETARIA MUNICIPAL DE ADMINISTRACION Y FINANZAS<br>
                            DIRECCION ADMINISTRATIVA<br>
                            UNIDAD DE ALMACENES<br>
                                    KARDEX  FISICO VALORADO<br> 
                            </td>
 <td style="">
            </td>
                    </tr>
         <tr style="width: 100%; border: solid 1px black; background: #E7E7E7; text-align: center; font-size: 6pt;">
            <th style="width: 6%">FECHA</th>
            <th style="width: 6%">Produit</th>
            <th style="width: 52%">Désignation</th>
            <th style="width: 13%">Prix Unitaire</th>
            <th style="width: 10%">Quantité</th>
            <th style="width: 10%">Prix Net</th>
        </tr>    
       
        </thead>
<?php
    $nb = 250;//rand(120, 250);
    $produits = array();
    $total = 0;
    for ($k=0; $k<$nb; $k++) {
        $num = rand(100000, 999999);
        $nom = "le producto n°".rand(1, 100);
        $qua = rand(1, 20);
        $prix = rand(100, 9999)/100.;
        $total+= $prix*$qua;
        $produits[] = array($num, $nom, $qua, $prix, rand(0, $qua));
?>
        <tr style="width: 100%; border: solid 1px black; background: #F7F7F7; text-align: center; font-size: 6pt;">
            <td style="width: 6%; text-align: left"><?php echo $num; ?></td>
            <td style="width: 6%; text-align: left"><?php echo $num; ?></td>
            <td style="width: 52%; text-align: left"><?php echo $nom; ?></td>
            <td style="width: 13%; text-align: right"><?php echo number_format($prix, 2, ',', ' '); ?> </td>
            <td style="width: 10%"><?php echo $qua; ?></td>
            <td style="width: 10%; text-align: right;"><?php echo number_format($prix*$qua, 2, ',', ' '); ?> </td>
        </tr>
    
<?php
    }
?></table>
   
    <nobreak>
        <br>
        Dans cette attente, nous vous prions de recevoir, Madame, Monsieur, Cher Client, nos meilleures salutations.<br>
        <br>
     <table cellspacing="0" style="width: 100%; text-align: left;">
            <tr>
                <td style="width:50%;"></td>
                <td style="width:50%; ">
                    Mle Jesuis CELIBATAIRE<br>
                    Service Relation Client<br>
                    Tel : 33 (0) 1 00 00 00 00<br>
                    Email : on_va@chez.moi<br>
                </td>
            </tr>
        </table>
    </nobreak>
</page>