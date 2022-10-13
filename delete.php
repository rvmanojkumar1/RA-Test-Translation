<?php

include_once 'database.php';

if(isset($_GET['deleteid'])){

    $id=$_GET['deleteid'];

    $select="SELECT toolbar_title, dbtable FROM public.custom_form WHERE ide=$id";

    $selectquery = $conn->prepare($select);

    $selectquery->execute();

    $form = $selectquery->fetch(PDO::FETCH_ASSOC);

    $name=$form['toolbar_title']."_".date("y_m_d_H_i_s");

    $table=$form['dbtable'];

    $newtable=$form['dbtable']."_".date("y_m_d_H_i_s");

    $sql4="UPDATE public.custom_form SET isdeleted=true, dbtable='".$newtable."', toolbar_title='".$name."' WHERE ide=$id";

    $sid = $conn->prepare($sql4);

    $sid->execute();

    $rename="ALTER TABLE ".$table." RENAME TO ".$newtable."";

    $conn->exec($rename);

    header("location:home.php");

}
else{
    header("location:home.php");
}
?>