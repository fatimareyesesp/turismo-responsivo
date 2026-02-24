"use strict";

            document.getElementById("mostrarAviso").addEventListener('click', function(mostrarAviso){
                mostrarAviso.preventDefault()
                alert("Aviso de Privacidad: Caribbean Tours respeta tu privacidad y protege tus datos personales. Recopilamos información de contacto, viaje y preferencias para procesar reservas, pagos y mejorar nuestros servicios. Tus datos pueden compartirse con proveedores necesarios. Puedes ejercer tus derechos escribiendo a privacidad@toursdelcaribe.com. Consulta nuestro aviso completo en el sitio web.");
            });
            
                document.getElementById("catamaran").addEventListener('click', function(mostrarInfo){
                    window.open(
                        "catamaran.html",
                         "card_1",
                          "width = 600px, height=600px"
                        );
                })
                document.getElementById("laguna").addEventListener('click',function(mostrarInfo){
                    window.open(
                        "laguna.html",
                         "card_1",
                          "width = 600px, height=600px"
                        );
                })
                document.getElementById("nado").addEventListener('click',function(mostrarInfo){
                    window.open(
                        "nado.html",
                         "card_1",
                          "width = 600px, height=600px"
                        );
                })