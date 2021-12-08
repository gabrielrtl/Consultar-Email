<?php
error_reporting(0);


if(isset($_POST['termo'])){
    $termo = $_POST['termo'];
}


// API
$url = "https://emailrep.io/query/{$termo}";
$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt ($ch,CURLOPT_HTTPHEADER, array('Key: ps1hho71drg1bqjs1apa46fkjpym8qrlp543q9nbesvubrxt','User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36'));
ob_start();
curl_exec($ch); 
curl_close($ch);
$file_contents = "";
$file_contents = ob_get_contents();
ob_end_clean();
// ----------------------------------------------------------
?>

<!-- Configurações basica com formulario de envio-->
<html lang="pt-BR">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>EMAIL</title>
</head>
<body>
    <style>
    #arte {
        text-align: center;
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
    }
    </style>
    <div id="arte">
    <h3>Consultar Email</h3>
    <form action="consultaEmail.php" method="post">
    EMAIL:
    <input type="email" required="required" class="input-text" name="termo" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"/><input type=submit value="Enviar"/>
    <p>EM TESTES</p>
    </form> </br>
<!-- -------------------------------------------------- -->

    <?php

if(isset($termo)){
    

    if ($obj = json_decode($file_contents)) {

        //echo "$file_contents";
        //echo "<br><br><br>";

        $inicio = explode('{"status": "', $file_contents);
        $fim = explode('"reason": "exceeded daily limit"}', $inicio[1]);
        $status = substr($inicio[1], 0,4);
        
        if ($status === "fail") {
            echo "LIMITE DE CONSULTAS DIARIA ATINGIDA";
        }else{

            echo "<br>Email: $obj->email<br>";
		  
            echo "Reputação: $obj->reputation<br>"; 
            
            if ($obj->suspicious == "true"){
                echo "Suspeito: Sim<br>";
            }else{ echo "Suspeito: Não<br>";}

            echo "Referencias: $obj->references<br>";

            if ($obj->blacklisted == "true"){
                echo "Malicioso: Sim<br>";
            }else{ echo "Malicioso: Não<br>";}

            if ($obj->malicious_activity == "true"){
                echo "Fraude ou Phishing: Sim<br>";
            }else{ echo "Fraude ou Phishing: Não<br>";}

            if ($obj->malicious_activity_recent == "true"){
                echo "Atividade inlegal recente: Sim<br>";
            }else{ echo "Atividade inlegal recente: Não<br>";}

            $inicio = explode('"credentials_leaked": ', $file_contents);
            $fim = explode(', "credentials_leaked',$inicio[1]);
            if ($fim[0] == "true"){echo "Vazamento detectado: Sim<br>";}
            else{echo "Vazamento detectado: Não<br>";}

            if ($obj->credentials_leaked_recent == "true"){
                echo "Vazamento recente detectado: Sim<br>";
            }else{ echo "Vazamento recente detectado: Não<br>";}

            $inicio = explode('"profiles": ["', $file_contents);
            $fim = explode('"]}, "',$inicio[1]);
            if ($fim[0] == "") {
                echo "";
            }else{echo "Utilizado em: $fim[0]<br>";}

            $inicio = explode('"first_seen": "', $file_contents);
            $fim = explode('", "last',$inicio[1]);
            echo "Primeiro vazamento de dados: $fim[0]<br>";

            $inicio = explode('"last_seen": "', $file_contents);
            $fim = explode('", "dom',$inicio[1]);
            echo "Ultimo vazamento de dados: $fim[0]<br>";

            $inicio = explode('"domain_reputation": "', $file_contents);
            $fim = explode('", "new',$inicio[1]);
            echo "Reputação dominio: $fim[0]<br>";

            $inicio = explode('_creation": ', $file_contents);
            $fim = explode(', "suspicious_',$inicio[1]);
            echo "Dias ativo: $fim[0]<br>";
        }
    }

}

    ?>
        
    </div>


</body>
</html>