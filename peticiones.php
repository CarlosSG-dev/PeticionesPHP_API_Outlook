<?php

//------------------------------------------------------------PETICIONES GET-------------------------------------------------------------------------//

$curl = curl_init();



curl_setopt_array($curl, array(

//SACAR CARPETAS
//CURLOPT_URL => 'https://graph.microsoft.com/v1.0//me/mailFolders/drafts', sin drafts, saca todas las carpetas del mail

//SACAR TODOS LOS MENSAJES
//CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/messages',

//SACAR MENSAJES DE X CARPETA EN ESPECÍFICO
// CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/mailfolders/inbox/messages' donde inbox = nombre(id) de la carpeta

//SACAR MENSAJES DE UN DESTINATARIO
// CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/messages?$filter=(from/emailAddress/address)%20eq%20\'AQUI_CORREO\'',
//EJEMPLO FUNCIONAL
// CURLOPT_URL => 'https://graph.microsoft.com/v1.0//me/mailFolders/inbox/messages?$filter=startsWith(from/emailAddress/address,'support@pccomponentes.com')'


//LISTAR REGLAS DE CORREO
//CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/mailFolders/inbox/messageRules' donde inbox = nombre(id) de la carpeta

//CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/mailFolders/inbox/messages?$select=id,receivedDateTime,subject,from&$filter=singleValueExtendedProperties/any(ep:ep/id eq 'String 0x5D01' and ep/value eq 'notificacionesg3@mrw.es') and contains(subject,'cuenta')'

//Buscar correos en todas las carpetas a partir de en el asunto un num de envio, remitente notificacionesg3@mrw.es, ultimo en fecha


// https://graph.microsoft.com/v1.0/me/mailFolders/inbox/messages?$orderby=receivedDateTime DESC,from/emailAddress/address&$filter=receivedDateTime ge 2016-01-01T00:00:00Z and from/emailAddress/address eq 'no-reply@gls-spain.dev' and contains(subject,'cuenta')&$select=receivedDateTime,from,subject,hasAttachments,bodyPreview


CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'GET',
CURLOPT_HTTPHEADER => array(
'Authorization: TOKEN'
),
));


$response = curl_exec($curl);

//var_dump($response);

curl_close($curl);

//------------------------------------------------PETICIONES POST/PATCH/DELETE----------------------------------------------------//

//PARA EL ENVIO DE DATOS, SI LOS DATOS ESTAN EN FORMATO ARRAY O ARRAY MULTIDIMENSIONAL USAREMOS LO SIGUIENTE: 

//UTILIZAREMOS json_encode() para pasar arrays a json para poder pasarlos por la peticion 

//EJ: 

// $datos = array(
//     'username' => 'tabulacion',
//     'password' => '123456'
// );
// $datojson = json_encode(array("usuario" => $datos));

//--------------------ENVIO DE EMAIL---------------//


//Abrimos la conexión cURL
$ch = curl_init();


$fields ='
    "message": {
      "subject": "Meet for lunch?",
      "body": {
        "contentType": "Text",
        "content": "The new cafeteria is open."
      },
      "toRecipients": [
        {
          "emailAddress": {
            "address": "fannyd@contoso.onmicrosoft.com"
          }
        }
      ],
      "ccRecipients": [
        {
          "emailAddress": {
            "address": "danas@contoso.onmicrosoft.com"
          }
        }
      ]
    },
    "saveToSentItems": "false"
  ';


//$fields_notJson = json_decode($fields);
//convertimos el arreglo en formato URL
//$fields_string = http_build_query($fields);

//var_dump($fields_notJson);
//var_dump($fields);
//var_dump(gettype($fields));

curl_setopt_array($ch, array(
   
    //Configuramos mediante CURLOPT_URL la URL destino
    CURLOPT_URL => "https://graph.microsoft.com/v1.0/me/sendMail",
    //Asignamos los campos a enviar en el POST
    CURLOPT_POSTFIELDS => $fields,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //Indicamos que se trata de una petición POST con valor "1" o "true"
    //curl_setopt($ch, CURLOPT_POST, 1);
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_HTTPHEADER => array(
    'Authorization: TOKEN',
    'Content-Type: application/json'
    )
    
    ));

//Ejecuta la petición HTTP y almacena la respuesta en la variable $data.
$data = curl_exec($ch);

//var_dump($fields_string);
//Cierra la conexión cURL
curl_close($ch);


//--------------------MOVER CARPETA---------------//

$curl = curl_init();


$datos = '{
    "destinationId": "destinationId-value"
  }';

curl_setopt_array($curl, array(


CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/mailFolders/{idDeLaCarpetaAMover}/move',


CURLOPT_POSTFIELDS => $datos,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'POST',
CURLOPT_HTTPHEADER => array(
'Authorization: TOKEN',
'Content-Type: application/json'),
));


$response = curl_exec($curl);


curl_close($curl);


//--------------------MOVER CORREO DE CARPETA---------------//

$curl = curl_init();

//EL DATO QUE SE PASAN EN LA PETICION ES LA CARPETA DE DESTINO
$datosCarpetaDestino = '{
    "destinationId": "deleteditems"
  }';

curl_setopt_array($curl, array(


CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/messages/AAMkADhAAATs28OAAA=/move',  // AAMkADhAAATs28OAAA= ES EL ID DEL MENSAJE


CURLOPT_POSTFIELDS => $datosCarpetaDestino,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'POST',
CURLOPT_HTTPHEADER => array(
'Authorization: TOKEN',
'Content-Type: application/json'),
));


$response = curl_exec($curl);


curl_close($curl);


//--------------------CREAR CARPETA---------------//

//https://docs.microsoft.com/es-es/graph/api/user-post-mailfolders?view=graph-rest-1.0&tabs=http

$curl = curl_init();

//la propiedad isHidden por defecto es false, una vez se pase la opción no se podrá cambiar,
//indicará si se desea ocultar la nueva carpeta o no
$newFolder = '{
    "displayName": "NombreDeLaCarpeta",
    "isHidden": false 
  }';

curl_setopt_array($curl, array(


CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/mailFolders',


CURLOPT_POSTFIELDS => $newFolder,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'POST',
CURLOPT_HTTPHEADER => array(
'Authorization: TOKEN DE ACCESO',
'Content-Type: application/json'),
CURLOPT_RETURNTRANSFER => true,
));


$response = curl_exec($curl);


curl_close($curl);


//--------------------CREAR, ACTUALIZAR O BORRAR REGLAS DE CORREO---------------//


// ------------------------CREAR----------------//

//https://docs.microsoft.com/es-es/graph/api/mailfolder-post-messagerules?view=graph-rest-1.0&tabs=http

$curl = curl_init();

//actions : Acciones que se van a realizar en un mensaje cuando las condiciones correspondientes, si las hubiera, se cumplan. Necesario.
//conditions: Condiciones que, cuando se cumplan, activarán las acciones correspondientes a esa regla. Opcional.
//displayName: Nombre para mostrar de la regla. Necesario.
//exceptions: Representa las condiciones de excepción de la regla. Opcional.
//isEnabled: Indica si la regla está habilitada para que se aplique a los mensajes. Opcional.
//sequence: Indica el orden en que se ejecuta la regla entre otras reglas. Necesario.
$datosRegla = '{      
    "displayName": "From partner",      
    "sequence": 2,      
    "isEnabled": true,          
    "conditions": {
        "senderContains": [
          "adele"       
        ]
     },
     "actions": {
        "forwardTo": [
          {
             "emailAddress": {
                "name": "Alex Wilbur",
                "address": "AlexW@contoso.onmicrosoft.com"
              }
           }
        ],
        "stopProcessingRules": true
     }    
}
';

curl_setopt_array($curl, array(


CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/mailFolders/inbox/messageRules',


CURLOPT_POSTFIELDS => $datosRegla,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'POST',
CURLOPT_HTTPHEADER => array(
'Authorization: TOKEN DE ACCESO',
'Content-Type: application/json'),
CURLOPT_RETURNTRANSFER => true,
));


$response = curl_exec($curl);


curl_close($curl);


// ------------------------ACTUALIZAR----------------//
// UTILIZAMOS PETICION PATCH, NO POST

//https://docs.microsoft.com/es-es/graph/api/messagerule-update?view=graph-rest-1.0&tabs=http

$curl = curl_init();

//actions : Acciones que se van a realizar en un mensaje cuando las condiciones correspondientes, si las hubiera, se cumplan. Necesario.
//conditions: Condiciones que, cuando se cumplan, activarán las acciones correspondientes a esa regla. Opcional.
//displayName: Nombre para mostrar de la regla. Necesario.
//exceptions: Representa las condiciones de excepción de la regla. Opcional.
//isEnabled: Indica si la regla está habilitada para que se aplique a los mensajes. Opcional.
//isReadOnly: Indica si la regla es de solo lectura y la API de REST de reglas no la puede modificar ni eliminar.
//sequence: Indica el orden en que se ejecuta la regla entre otras reglas. Necesario.
$datosRegla2 = '{
    "displayName": "Important from partner",
    "actions": {
        "markImportance": "high"
     }
}
';

curl_setopt_array($curl, array(


CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/mailFolders/inbox/messageRules/AQAAAJ5dZqA=', //donde AQAAAJ5dZqA= es el id


CURLOPT_POSTFIELDS => $datosRegla2,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'PATCH',
CURLOPT_HTTPHEADER => array(
'Authorization: TOKEN DE ACCESO',
'Content-Type: application/json'),
CURLOPT_RETURNTRANSFER => true,
));


$response = curl_exec($curl);


curl_close($curl);



//-----------------------ELIMINAR REGLA ------------------//

//https://docs.microsoft.com/es-es/graph/api/messagerule-delete?view=graph-rest-1.0&tabs=http

//PETICION DELETE

$curl = curl_init();

//actions : Acciones que se van a realizar en un mensaje cuando las condiciones correspondientes, si las hubiera, se cumplan. Necesario.
//conditions: Condiciones que, cuando se cumplan, activarán las acciones correspondientes a esa regla. Opcional.
//displayName: Nombre para mostrar de la regla. Necesario.
//exceptions: Representa las condiciones de excepción de la regla. Opcional.
//isEnabled: Indica si la regla está habilitada para que se aplique a los mensajes. Opcional.
//isReadOnly: Indica si la regla es de solo lectura y la API de REST de reglas no la puede modificar ni eliminar.
//sequence: Indica el orden en que se ejecuta la regla entre otras reglas. Necesario.
$datosRegla3 = '{
    "displayName": "Important from partner",
    "actions": {
        "markImportance": "high"
     }
}
';

curl_setopt_array($curl, array(


CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/mailFolders/inbox/messageRules/AQAAAJ5dZp8=', //donde AQAAAJ5dZp8= es el id 


CURLOPT_POSTFIELDS => $datosRegla3,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'DELETE',
CURLOPT_HTTPHEADER => array(
'Authorization: TOKEN DE ACCESO',
'Content-Type: application/json'),
CURLOPT_RETURNTRANSFER => true,
));


$response = curl_exec($curl);


curl_close($curl);

?>